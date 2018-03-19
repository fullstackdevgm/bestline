<?php

use Order\OrderData;
use Company\BillingAddress;
use Company\ShippingAddress;
use Order\Fabric as OrderFabric;
use Order\Option as OrderOption;
use Bestline\Event\Events;
use Bestline\Utils;
use Fabric\Type;
use Carbon\Carbon;
use ZendPdf\PdfDocument;
use ZendPdf\Font;
use ZendPdf\Page as Zend_Pdf_Page;
use ZendPdf\Color\Html;

class OrderController extends Controller
{
    /**
     * @var Order
     */
    protected $_order;

    public function __construct(Order $order) {
        $this->_order = $order;
    }

    public function destroy($orderId)
    {
        $order = Order::find($orderId);
        $order->delete();

        return Redirect::route('dashboard');
    }

    public function create()
    {
        $orderId = Input::get('order', null);

        $order = Order::find($orderId);

        $companies = Company::orderBy('name')->lists('name', 'id');
        $states = State::orderBy('abbreviation')->lists('state', 'abbreviation');
        $creditTerms = Company::getCreditTerms();
        $customerType = CustomerType::all()->lists('name', 'id');
        $shippingMethods = ShippingMethod::all()->lists('name', 'id');
        $fabrics = Type::with('fabrics')
                       ->where('type', '=', Type::TYPE_FACE)
                       ->first()
                       ->fabrics()
                       ->orderBy('pattern', 'asc')
                       ->get()
                       ->lists('name', 'id');
        $options = Option::findBaseOptions()->lists('name', 'id');
        $products = Product::all()->lists('name', 'id');
        asort($products);

        $ringTypes = RingType::all()->lists('description', 'id');

        return View::make('order.create', compact('companies', 'states', 'creditTerms', 'customerType', 'shippingMethods', 'order', 'fabrics', 'options', 'products', 'ringTypes'));
    }

	public function edit($orderId)
	{
		$order = Order::with(
				'billingAddress',
				'customerType',
				'shippingMethod',
				'company',
				'shippingAddress'
		)->find($orderId);

		$shippingMethods = ShippingMethod::lists('name', 'id');

		$orderLines = array();

		$products = Product::all()->lists('name', 'id');
		asort($products);

		$ringTypes = RingType::all()->lists('description', 'id');

		return View::make('order.step2', compact('order', 'orderLines', 'shippingMethods', 'products', 'ringTypes'));
	}

	public function quoteConfirmationInvoice($orderId)
	{
		$order = Order::find($orderId);

		if(!$order instanceof Order) {
		    App::abort(404);
		}

		$order->date_due = Carbon::createFromFormat('Y-m-d', $order->date_due);
		$order->date_received = Carbon::createFromFormat('Y-m-d', $order->date_received);

		$pdf = PdfDocument::load(\Config::get('bestline.invoice_template'));

		$pageIndex = 0;
		$pdf->pages[$pageIndex]->setFillColor(Html::namedColor('white'))->drawRectangle(420, 115, 580, 10, Zend_Pdf_Page::SHAPE_DRAW_FILL)->setFillColor(Html::namedColor('black')); //covers unused portions of the bottom of the default pdf
		static::pdfBuildPageNo($pdf, $pageIndex);
		$pdf->pages[0]->setFont(Font::fontWithName(Font::FONT_HELVETICA), 17)->setFillColor(Html::namedColor('black'));

		if($order->is_quote){
			$pdf->properties['Title'] = 'Bestline Quote';
			$pdf->pages[0]->setFillColor(Html::namedColor('white'))->drawRectangle(370, 760, 570, 735)->setFillColor(Html::namedColor('black'))->drawText("QUOTE #{$order->id}", 375, 742);
		} else if(!$order->finalized){
			$pdf->properties['Title'] = 'Bestline Confirmation';

			//cover Invoice in template with confirmation
			$pdf->pages[0]->setFillColor(Html::namedColor('gray'))->drawRectangle(370, 760, 570, 735)->setFillColor(Html::namedColor('white'))->drawText("CONFIRMATION #{$order->id}", 375, 742);
		} else {
			$pdf->properties['Title'] = 'Bestline Invoice';
			$pdf->pages[0]->drawRectangle(370, 760, 570, 735)->setFillColor(Html::namedColor('white'))->drawText("INVOICE #{$order->id}", 375, 742);
		}

		$pdf->pages[0]->setFont(Font::fontWithName(Font::FONT_HELVETICA), 11)->setFillColor(Html::namedColor('black'));

		$pdf->pages[0]->drawText($order->sidemark, 433, 720);
		$pdf->pages[0]->drawText($order->purchase_order, 393, 707);
		$pdf->pages[0]->drawText($order->credit_terms, 495, 706);
		$pdf->pages[0]->drawText($order->date_received->format('m/d/Y'), 455, 692);
		if(strtotime($order->date_due) > 0){
			$pdf->pages[0]->drawText($order->date_due->format('m/d/Y'), 465, 678);
		} else {
			$pdf->pages[0]->drawText('Not set', 465, 678);
		}

		$pdf->pages[0]->setFont(Font::fontWithName(Font::FONT_HELVETICA), 9)->setFillColor(Html::namedColor('black'));

		$phoneNumber = Utils::formatPhoneNumber($order->company->phone_number);

		$YOffset = 621;
		$pdf->pages[0]->drawText("{$order->company->name}", 215, $YOffset); $YOffset = $YOffset - 9;
		$pdf->pages[0]->drawText($order->shippingAddress->address1, 215, $YOffset); $YOffset = $YOffset - 9;
		if($order->shippingAddress->address2){
		    $pdf->pages[0]->drawText($order->shippingAddress->address2, 215, $YOffset); $YOffset = $YOffset - 9;
		}
		$pdf->pages[0]->drawText("{$order->shippingAddress->city}, {$order->shippingAddress->state} {$order->shippingAddress->zip}", 215, $YOffset); $YOffset = $YOffset - 9;
		$pdf->pages[0]->drawText("$phoneNumber", 215, $YOffset);

		$YOffset = 621;
		$pdf->pages[0]->drawText("{$order->company->name}", 30, $YOffset); $YOffset = $YOffset - 9;
		$pdf->pages[0]->drawText($order->billingAddress->address1, 30, $YOffset); $YOffset = $YOffset - 9;
		if($order->billingAddress->address2){
		    $pdf->pages[0]->drawText($order->billingAddress->address2, 30, $YOffset); $YOffset = $YOffset - 9;
		}
		$pdf->pages[0]->drawText("{$order->billingAddress->city}, {$order->billingAddress->state} {$order->billingAddress->zip}", 30, $YOffset); $YOffset = $YOffset - 9;
		$pdf->pages[0]->drawText("$phoneNumber", 30, $YOffset); $YOffset = $YOffset - 15;

		if($order->contact){
			if($order->contact->full_name){
				$pdf->pages[0]->drawText("{$order->contact->full_name}", 30, $YOffset); $YOffset = $YOffset - 9;
			}
			if($order->contact->phone_number->number !==  'No phone added.'){
				$contactPhone = Utils::formatPhoneNumber($order->contact->phone_number->number);
				$pdf->pages[0]->drawText("{$contactPhone}", 30, $YOffset); $YOffset = $YOffset - 9;
			}
			if($order->contact->email->email !== 'No email added.'){
				$pdf->pages[0]->drawText("{$order->contact->email->email}", 30, $YOffset); $YOffset = $YOffset - 9;
			}
		}

		if(!$order->finalized && !$order->is_quote){
			$confirmationEnd = Carbon::parse($order->date_due)->subWeek()->toFormattedDateString();
			$confirmationMessage = "Please review this order and contact us before $confirmationEnd to make any changes";
			$pdf->pages[0]->setFillColor(Html::namedColor('red'))->drawText($confirmationMessage, 100, 540);
		}

		$pdf->pages[0]->setFillColor(Html::namedColor('black'))->setFont(Font::fontWithName(Font::FONT_HELVETICA), 9);

		// fabric data
		// using 8pt font
		$pdf->pages[0]->setFont(Font::fontWithName(Font::FONT_TIMES_BOLD), 13);
		$pdf->pages[0]->drawText("Fabrics:", 360, 635);
		$pdf->pages[0]->setFont(Font::fontWithName(Font::FONT_HELVETICA), 9);
		$fabricYOffset = 621;
		foreach ($order->fabrics as $orderFabric) {

			$fabricTypeAndName = "{$orderFabric->type->name}: {$orderFabric->fabric->pattern} / {$orderFabric->fabric->color}";
			if($orderFabric->fabric->grade){
				$fabricTypeAndName = $fabricTypeAndName . ", Grade: {$orderFabric->fabric->grade}";
			}

			if($orderFabric->fabric->owner_company_id){
				$fabricText = "{$fabricTypeAndName}, Total Yards: ". ceil($orderFabric->total_length);
			} else {
				$fabricText = "{$fabricTypeAndName}";
			}

		    $pdf->pages[0]->drawText($fabricText, 370, $fabricYOffset);
		    $fabricYOffset -= 10;
		}
		// return to 9pt font
		$pdf->pages[0]->setFont(Font::fontWithName(Font::FONT_HELVETICA), 9);

		// build line items
		$YOffset = 500;
		$pdf = OrderController::pdfBuildLineItemLines($order, $pdf, $YOffset);

		$lastPageIndex = count($pdf->pages) - 1;
		static::pdfBuildTotals($order, $pdf, $lastPageIndex);

		return \Response::make($pdf->render(), 200, ['content-type' => 'application/pdf']);
	}

	public static function pdfBuildTotals($order, $pdf, $pageIdx){

		$order->invoice_notes = str_replace("\n", '', $order->invoice_notes);

		$noteLines = explode("\n", wordwrap($order->invoice_notes, 80, "\n", true));

		$noteYOffset = 105;
		foreach($noteLines as $noteLine) {
		    $pdf->pages[$pageIdx]->drawText($noteLine, 70, $noteYOffset);
		    $noteYOffset -= 10;
		}

		if(!$order->finalized){
			$subTotal = $order->subtotal;
			$discountTotal = $order->discount_total;
			$rushTotal = $order->rush_total;
			$total = $order->total;
		} else {
			$subTotal = $order->finalized->subtotal;
			$discountTotal = $order->finalized->discount_total;
			$rushTotal = $order->finalized->rush_total;
			$total = $order->finalized->total;
		}

		$totals = [];
		$totals[] = array('title' => 'Subtotal:', 'value' => $subTotal);
		$totals[] = array('title' => 'Deposit Amt:', 'value' => $order->deposit_amount);
		$totals[] = array('title' => 'Discount:', 'value' => $discountTotal);
		$totals[] = array('title' => 'Rush:', 'value' => $rushTotal);
		$totals[] = array('title' => 'Boxing:', 'value' => $order->boxing_cost);
		$totals[] = array('title' => 'Shipping:', 'value' => ($order->shipping_amount)? $order->shipping_amount: "0.00");
		$totals[] = array('title' => 'Total Due:', 'value' => $total);

		$beginningY = 105;
		foreach($totals as $total){
			$value = floatval($total['value']);
			if(floatval($total['value']) > 0){
				$pdf->pages[$pageIdx]->drawText("{$total['title']}", 430, $beginningY);
				$pdf->pages[$pageIdx]->drawText("\${$total['value']}", 500, $beginningY);
				$beginningY -= 11;
			}
		}

		return $pdf;
	}

	public static function pdfBuildLineItemLines($order, ZendPdf\PdfDocument $pdf, $YOffset) {
	    $orderLineNum = 1;
	    $pageIdx = 0;

	    // set up additional pages template
	    $pdf->pages[1]->setFont(Font::fontWithName(Font::FONT_HELVETICA), 9);
	    $pdf->pages[1]->setFillColor(Html::namedColor('white'))->drawRectangle(420, 115, 580, 10, Zend_Pdf_Page::SHAPE_DRAW_FILL)->setFillColor(Html::namedColor('black')); //covers unused portions of the bottom of the default pdf

	    // additional pages use second page in template PDF
	    $additionalPage = $pdf->pages[1];
	    // get rid of second page now that we have it saved
	    unset($pdf->pages[1]);

	    foreach($order->orderLines as $orderLine) {
	        $pdf->pages[$pageIdx]->drawText($orderLineNum, 30, $YOffset);
	        $pdf->pages[$pageIdx]->drawText($orderLine->pretty_total_price, 530, $YOffset);

	        if($orderLine->return > 0){
	        	$return = '+ ' . $orderLine->return;
	        } else {
	        	$return = '';
	        }

	       	if($orderLine->has_shade){
	       		$mountDescription = ($orderLine->mount && $orderLine->mount->description)? $orderLine->mount->description: "No mount";
	       		$pullTypeDescription = ($orderLine->pullType && $orderLine->pullType->description)? $orderLine->pullType->description: "No pull type";
	       		$cordPositionDescription = ($orderLine->cord_position && $orderLine->cord_position->description)? $orderLine->cord_position->description: "No cord position";
	        	$styleStr = "Shade Style: {$orderLine->product->name} ({$orderLine->width} $return x {$orderLine->height}) -- ({$orderLine->hardware->description}: {$pullTypeDescription}, {$cordPositionDescription} x {$orderLine->cord_length} {$mountDescription})";

		        // see if we need an extra line for the shade style
		        if (strlen($styleStr) > 95) {
		            $styleStrings = explode("\n", wordwrap($styleStr, 95, "\n", true));
		            $styleStrLineNum = 1;

		            foreach ($styleStrings as $s) {
		                $pdf->pages[$pageIdx]->drawText($s, 100, $YOffset);
		                $YOffset -= 10;
		                $styleStrLineNum++;
		            }

		        } else {
		            // no line wrapping needed
		            $pdf->pages[$pageIdx]->drawText($styleStr, 100, $YOffset);
		            $YOffset -= 10;
		        }

		        //add shade price
		        $shadeCharge = $orderLine->shade_price + $orderLine->fabric_price;
		        $shadeChargeRow = "Shade Charge: \${$shadeCharge}";
		        $pdf->pages[$pageIdx]->drawText($shadeChargeRow, 120, $YOffset);
		        $YOffset -= 10;
	        }

	        if($orderLine->has_valance) {
	            $valanceStr = "Valance Style: {$orderLine->valance_type->name} {$orderLine->valance_width} (W), {$orderLine->valance_height} (H), {$orderLine->valance_return} (R)";
	            $pdf->pages[$pageIdx]->drawText($valanceStr, 100, $YOffset);
	            $YOffset -= 10;

	            //add valance price
	            $valanceCharge = "Valance Charge: \${$orderLine->valance_price}";
	            $pdf->pages[$pageIdx]->drawText($valanceCharge, 120, $YOffset);
	            $YOffset -= 10;
	        }

	        foreach($orderLine->options as $option) {
	            $optionStr = "Option Charge: {$option->option->name} / {$option->sub_option->name} = {$option->pretty_price}";
	            $pdf->pages[$pageIdx]->drawText($optionStr, 100, $YOffset);
	            $YOffset -= 10;
	        }

	        $YOffset -= 10;
	        $orderLineNum++;

	        // do we need a new page?
	        if($YOffset <= 160) {
	            $pageIdx++;
	            $pdf->pages[$pageIdx] = clone $additionalPage;
	            static::pdfBuildPageNo($pdf, $pageIdx);
	            $pdf->pages[$pageIdx]->setFont(Font::fontWithName(Font::FONT_HELVETICA), 9);
	            $YOffset = 740;
	        }
	    }

	    return $pdf;
	}

	public static function pdfBuildPageNo($pdf, $pageIndex){
		$pageNo = $pageIndex + 1;
		$pdf->pages[$pageIndex]->setFont(Font::fontWithName(Font::FONT_HELVETICA), 9);
		$pdf->pages[$pageIndex]->drawText("Page: {$pageNo}", 17, 105);
	}
}
