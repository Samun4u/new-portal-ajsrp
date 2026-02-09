@if(count($orders)>0)
<option value="">{{__("Select Order")}}</option>
@foreach($orders as $item)
<option value="{{$item->id}}">
    {{$item->order_id}}, {{__('Price-')}} ({{showPrice($item->total)}}),
    {{__('Due-')}} ({{showPrice($item->total - $item->transaction_amount)}})
</option>
@endforeach
@endif