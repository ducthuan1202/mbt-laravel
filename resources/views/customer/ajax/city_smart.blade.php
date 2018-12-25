@php
/**
* @var $customers array
* @var $customerGroupByCity array
* @var $cities array
* @var $cityId string
*/
@endphp
<option value="0">--- Chọn khu vực ---</option>
@if(count($cities))
    @foreach($cities as $city)
        <option value="{{ $city['id'] }}" {{$city['id'] == $cityId ? 'selected' : ''}}>
            {{sprintf('%s (có %s KH)', $city['name'], count($customerGroupByCity[$city['id']]))}}
        </option>
    @endforeach
@endif