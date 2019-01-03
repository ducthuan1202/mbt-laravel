<option value="0">--- Chọn khách hàng ---</option>
@if(count($customers))
    @foreach($customers as $customer)
        <option value="{{ $customer['id'] }}" {{$customer['id'] == $customerId ? 'selected' : ''}}>
            @if(isset($customer['mobile']))
                {{sprintf('%s (%s - %s)', $customer['name'], $customer['mobile'], $customer['company'])}}
            @else
                {{sprintf('%s', $customer['name'])}}
            @endif
        </option>
    @endforeach
@endif