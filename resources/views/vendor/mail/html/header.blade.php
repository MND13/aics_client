<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
                <img style="max-height:auto; max-width:200px" src="https://fo11.dswd.gov.ph/wp-content/uploads/2021/05/DSWD-XI-Davao-1536x685.png" contain alt="DSWD Field Office XI" />
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>
