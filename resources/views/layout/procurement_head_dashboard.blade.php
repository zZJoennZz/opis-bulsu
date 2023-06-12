<div class="table-responsive">
    <table class="table table-small border-dark caption-top" id="pending-item-changes">
        <caption class="text-uppercasea small">Pending Item Changes</caption>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Updates By</th>
                <th class="text-end">Review</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($allItemDetails as $item)
                @php ($historyCount = count($item->histories))
                @if($historyCount >= 1)
                    @if ($item->histories[$historyCount - 1]->is_approve === 0)
                        <tr>
                            <td>{{$item->histories[$historyCount - 1]->item_detail->description}}</td>
                            <td>{{$item->histories[$historyCount - 1]->user->profile->first_name}} {{$item->histories[$historyCount - 1]->user->profile->last_name}}</td>
                            <td class="text-end"><a href="{{ route('pending-item-detail.single', ['item_detail_id' => $item->id]) }}" class="btn btn-sm btn-primary"><em class="bi bi-eye"></em></a></td>
                        </tr>
                    @endif
                @endif
            @endforeach
        </tbody>
    </table>
</div>