@if(count($customers))
    @foreach($customers as $customer)

        <option value="{{ $customer['id'] }}" {{$customer['id'] === $customerId ? 'selected' : ''}}>
            @if(isset($customer['mobile']))
                {{sprintf('%s (%s)', $customer['name'], $customer['mobile'])}}
            @else
                {{sprintf('%s', $customer['name'])}}
            @endif
        </option>
    @endforeach
@else

@endif