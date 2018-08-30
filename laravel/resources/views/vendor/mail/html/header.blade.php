<tr>
    <td class="header">
        <a href="{{ $url }}">
            @if($logo != null)
                <img src="{{ $logo }}" alt="{{ $location_name }}">
            @else
                <h2 class="salon-name">{{ $location_name }}</h2>
            @endif
        </a>
    </td>
</tr>
