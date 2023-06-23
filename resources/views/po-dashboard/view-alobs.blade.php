<x-dashboard-layout>
    <x-slot:title>
        Allotment and Obligation Slip | {{ $alobs->purchase_order->po_number }}
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Allotment and Obligation Slip', 'route' => 'alobs.all'],
            ['name' => 'Complete Form']
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    
    <form action="{{ route('alobs.update') }}/{{ $alobs->id }}" method="POST">
        @csrf
        @method("PUT")
        <div class="mb-3">
            <label for="budget_officer" class="form-label">Budget Officer:</label>
            <select name="budget_officer" id="budget_officer" class="form-select" required>
                <option value="" disabled selected hidden>Select budget officer</option>
                @foreach ($bo_users as $user)
                    <option value="{{ $user->id }}" @if ($alobs->budget_officer_id === $user->id)
                        selected
                    @endif>{{ $user->profile->first_name }} {{ $user->profile->last_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-primary">
                Complete <em class="bi bi-chevron-double-right"></em>
            </button>
        </div>

    </form>
    
    <x-slot:additional_script>
        
    </x-slot>
</x-dashboard-layout>