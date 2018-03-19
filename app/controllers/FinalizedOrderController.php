<?php

use ZendPdf\PdfDocument;
use ZendPdf\Font;
use Carbon\Carbon;

class FinalizedOrderController extends Controller
{
    //setup views
    public function view()
    {
        $orderId = Input::get('order', null);

        $order = Order::find($orderId);

        if(!$order instanceof Order) {
            App::abort(404);
        }

        return Response::view('order.finalized.view', compact('order'));
    }

    public function ticket()
    {
        return \View::make('order.finalized.ticket');
    }

    public function labels()
    {
        return \View::make('order.finalized.ticket.labels');
    }

    public function destroy()
    {
        $orderId = Input::get('order', null);

        $order = Order::find($orderId);

        if(!$order instanceof Order) {
            App::abort(404);
        }

        $order->delete();

        return Redirect::to('/dashboard')->with('message', 'Order Deleted');
    }
}
