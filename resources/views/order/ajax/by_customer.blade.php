@if(count($orders))
    @foreach($orders as $order)
        <option value="{{ $order['id'] }}" {{$order['id'] == $orderId ? 'selected' : ''}}>
            {{sprintf('%s', $order['code'])}}
        </option>
    @endforeach
@endif