@section('javascript-head')
    @parent
    <script src="/js/vendor/bower_components/svg-pan-zoom/dist/svg-pan-zoom.min.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-svg-pan-zoom.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-viewbox.js" type="application/javascript"></script>
    <script src="/js/source/angular/directives/bestline-svg-measurement.js" type="application/javascript"></script>
    <script src="/js/source/angular/controllers/ticket/sidetabs.js" type="application/javascript"></script>
@stop
<div class="sidetabs" ng-controller="SideTabsController as sidetabs">
  <h2>Side Tabs Ticket</h2>
  <div class="row" style="padding-bottom: 20px;">
    <div class="col-xs-6">
        {[{ finalizedOrder.company.name }]}<br/>
        {[{ finalizedOrder.sidemark }]}
    </div>
    <div class="col-xs-6 text-right">
        Invoice: {[{ finalizedOrder.id }]}<br>
        Due Date: {[{ getDate(finalizedOrder.date_due) }]}
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <b>Total Side Tabs: {[{ sidetabs.drawing.itemsCnt }]}</b>
        </div>
        <div class="panel-body">
          
          <svg class="svgContainer" version="1.1"
              bestline-svg-pan-zoom control-icons-enabled="true" fit="1" center="1" contain="1" max-zoom="3" pan-zoom="sidetabs.panZoom">
              
              <svg x="0" y="0" style="overflow: visible;"
                  ng-attr-width="{[{ 200  }]}" 
                  ng-attr-height="{[{ 100 }]}" 
                  bestline-viewbox="0 0 {[{ 200 }]} {[{ 100 }]}" >                    
                  <rect stroke="none" opacity="0"
                      width="200"
                      height="100"
                  />
                  
                  <g ng-attr-transform="translate({[{ 10 }]}, {[{ 10 }]})">
                      <svg x="0" y="0"  style="overflow: visible;" preserveAspectRatio="xMidYMid meet"
                          ng-attr-width="{[{ 180 }]}" 
                          ng-attr-height="{[{ 80 }]}"
                          bestline-viewbox="0 0 {[{ sidetabs.drawing.width }]} {[{ sidetabs.drawing.height }]}">

                          {{-- board --}}
                          <rect fill="#FFFFFF" stroke="#000000" stroke-width="{[{ sidetab.lineWidth }]}"
                              ng-attr-y="{[{ sidetab.y  }]}"
                              ng-attr-x="{[{ sidetab.x }]}"
                              ng-attr-width="{[{ sidetab.width }]}" 
                              ng-attr-height="{[{ sidetab.height }]}"
                              ng-repeat="sidetab in finalizedOrder.sidetabs"
                          />

                          {{-- Label --}}
                          <text alignment-baseline="central" paint-order="stroke" stroke="white" font-size="0"
                              stroke-width="{[{ sidetab.lineWidth * 2 }]}"
                              ng-repeat="sidetab in finalizedOrder.sidetabs"
                              ng-attr-font-size="{[{ sidetabs.drawing.fontSize }]}" 
                              ng-attr-x="{[{ sidetab.x }]}" 
                              ng-attr-y="{[{ sidetabs.drawing.titleRowY }]}">  
                              Items ({[{ sidetab.items.length }]}): <tspan ng-repeat="item in sidetab.items">{[{ item }]}<tspan ng-show="!$last">,</tspan> </tspan>
                          </text>

                          <bestline-svg-measurement measurements="sidetabs.measurements"/>

                      </svg>
                  </g>
              </svg>
          </svg>
        </div>
      </div>
    </div>
</div>
</div>
