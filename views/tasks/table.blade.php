<table class="table table-bordered">
    <thead class="thead-light">
    <tr>
        <th>#</th>
        <th>{{ __('scraper::global.status') }}</th>
        <th>{{ __('scraper::global.parser') }}</th>
        <th>{{ __('scraper::global.source_url') }}</th>
        <th>{{ __('scraper::global.document') }}</th>
        <th>{{ __('scraper::global.parsing_date') }}</th>
        <th>{{ __('scraper::global.creating_date') }}</th>
        <th>{{ __('scraper::global.actions') }}</th>
    </tr>
    </thead>

    <tbody>
    @foreach($history as $item)
        @include('scraper::tasks.row-default')
    @endforeach

    @if($history->count() < config('scraper.tasks_per_page'))
        @for($i = 0; $i < config('scraper.tasks_per_page') - $history->count(); $i++)
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <button disabled class="invisible">ㅤ</button>
                </td>
            </tr>
        @endfor
    @endif

    </tbody>
    <tfoot>
    <tr>
        <td>
            <div class="d-flex">
                <button id="reset-mass-action-checkboxes"><i class="fa fa-square-o" aria-hidden="true"></i>
                </button>
                <button id="set-all-mass-action-checkboxes"><i class="fa fa-check-square-o"
                                                               aria-hidden="true"></i></button>
            </div>
        </td>
        <td colspan="7">
            <div id="massActions" style="display: none;" hx-boost="true">
                <span>Действия со списком:</span>
                <form class="d-inline" hx-include=".checkbox_mass_action" hx-indicator="#loadingIndicator"
                      action="{{ route('scraper::tasks.mass_action.delete') }}"
                      method="post">
                    @csrf
                    <button type="submit"
                            class="btn btn-danger">{{ __('scraper::global.to_delete') }}</button>
                </form>

                <form class="d-inline" hx-include=".checkbox_mass_action" hx-indicator="#loadingIndicator"
                      action="{{ route('scraper::tasks.mass_action.process') }}"
                      method="post">
                    @csrf
                    <button type="submit"
                            class="btn btn-success">{{ __('scraper::global.to_process') }}</button>
                </form>
                <span id="loadingIndicator" class="spinner-border spinner-border-sm htmx-indicator ml-1"
                      role="status" aria-hidden="true"></span>
            </div>
            <div id="infoField">
                {{ __('scraper::global.tasks_count') }}: {{ $history->total() }}
            </div>
        </td>
    </tr>
    </tfoot>
</table>
