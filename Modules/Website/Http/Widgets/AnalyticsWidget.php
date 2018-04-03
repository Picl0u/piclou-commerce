<?php
 namespace Modules\Website\Http\Widgets;

 use App\Http\Picl0u\AdminWidgetInterface;
 use Spatie\Analytics\AnalyticsFacade as Analytics;
 use Spatie\Analytics\Period;

 class AnalyticsWidget implements AdminWidgetInterface
 {
     public function render()
     {
         $analyticsData = Analytics::fetchVisitorsAndPageViews(Period::days(7));
         dd($analyticsData);
         return view("website::admin.widget.analytics");
     }

 }