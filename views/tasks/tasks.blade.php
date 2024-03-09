<div id="tasks-list" hx-target="this">
    {{--selector for sort tasks by status--}}
    <form hx-boost="true" action="{{ route('scraper::index') }}" hx-trigger="change" method="post" class="mb-1">
        <select name="status">
            <option value="-1" {{ $currentStatusFilter === null ? 'selected' : '' }}>Все задания</option>
            @foreach($statuses as $item)
                <option {{ ($currentStatusFilter != null && $currentStatusFilter == $item->value) ? 'selected' : '' }} value="{{ $item->value }}">{{ $item->name }}</option>
            @endforeach
        </select>
        @csrf
    </form>

    @if($history->isNotEmpty())

        {{--tasks table--}}
        <div class="table-responsive">
            @include('scraper::tasks.table')
        </div>

        {{--paginator--}}
        <div hx-boost="true">
            {{ $history->onEachSide(2)->links("pagination::bootstrap-4") }}
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                checkCheckboxes(); // Проверяем состояние чекбоксов при загрузке страницы
            });

            function checkCheckboxes() {
                const checkboxes = document.querySelectorAll(".checkbox_mass_action");
                const hasChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

                const massActions = document.getElementById("massActions");
                const infoField   = document.getElementById("infoField");

                massActions.style.display = hasChecked ? "block" : "none";
                infoField.style.display   = hasChecked ? "none" : "block";
            }

            document.querySelector("#reset-mass-action-checkboxes").addEventListener("click", function () {
                document.querySelectorAll(".checkbox_mass_action").forEach(function (checkbox) {
                    checkbox.checked = false;
                    checkCheckboxes();
                });
            });
            document.querySelector("#set-all-mass-action-checkboxes").addEventListener("click", function () {
                document.querySelectorAll(".checkbox_mass_action").forEach(function (checkbox) {
                    checkbox.checked = true;
                    checkCheckboxes();
                });
            });
        </script>
    @else
        <span>{{ __('scraper::global.no_tasks') }}</span>
    @endif
</div>
