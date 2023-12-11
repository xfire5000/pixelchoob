<table>
    <thead>
        <tr>
            <th>{{ __('export.description') }}</th>
            <th colspan="4">
                {{ __('export.chamfer') }}&nbsp;-&nbsp;
                @foreach ($fourSections as $item)
                    <div>{{ $item }}</div>
                @endforeach
            </th>
            <th colspan="2">
                {{ __('export.gazor_hinge') }}&nbsp;-&nbsp;
                @foreach ($twoSections as $item)
                    <div>{{ $item }}</div>
                @endforeach
            </th>
            <th colspan="2">
                {{ __('export.groove') }}&nbsp;-&nbsp;
                @foreach ($twoSections as $item)
                    <div>{{ $item }}</div>
                @endforeach
            </th>
            <th colspan="4">
                {{ __('export.pvc') }}&nbsp;-&nbsp;
                @foreach ($fourSections as $item)
                    <div>{{ $item }}</div>
                @endforeach
            </th>
            <th>{{ __('export.qty') }}</th>
            <th>{{ __('export.width') }}</th>
            <th>{{ __('export.height') }}</th>
            <th>{{ __('export.index') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($list_case->listItems as $key => $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td>{{ json_decode($item->chamfer)->w2 ? 1 : 0 }}</td>
                <td>{{ json_decode($item->chamfer)->w1 ? 1 : 0 }}</td>
                <td>{{ json_decode($item->chamfer)->l2 ? 1 : 0 }}</td>
                <td>{{ json_decode($item->chamfer)->l1 ? 1 : 0 }}</td>
                <td>{{ json_decode($item->gazor_hinge)->w ? 1 : 0 }}</td>
                <td>{{ json_decode($item->gazor_hinge)->l ? 1 : 0 }}</td>
                <td>{{ json_decode($item->groove)->w ? 1 : 0 }}</td>
                <td>{{ json_decode($item->groove)->l ? 1 : 0 }}</td>
                <td>{{ json_decode($item->pvc)->w2 ? 1 : 0 }}</td>
                <td>{{ json_decode($item->pvc)->w1 ? 1 : 0 }}</td>
                <td>{{ json_decode($item->pvc)->l2 ? 1 : 0 }}</td>
                <td>{{ json_decode($item->pvc)->l1 ? 1 : 0 }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ json_decode($item->dimensions)->w }}</td>
                <td>{{ json_decode($item->dimensions)->h }}</td>
                <td>{{ $key + 1 }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
