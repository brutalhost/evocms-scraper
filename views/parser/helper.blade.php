<div class="card">
    <div class="card-body">
        @if(app()->getLocale() == 'ru')
            @include('scraper::parser.helper.ru')
        @else
            @include('scraper::parser.helper.en')
        @endif

        <form class="d-inline mr-1" method="post"
              action="{{ route('scraper::tasks.console.mark-completed-as-created') }}">
            <button class="btn btn-primary mb-1" type="submit">{{ __('scraper::global.change_status') }}</button>
            @csrf
        </form>
        <form class="d-inline" method="post" action="{{ route('scraper::tasks.console.delete-all') }}">
            <button class="btn btn-danger mb-1" type="submit">{{ __('scraper::global.delete_all_tasks') }}</button>
            @csrf
        </form>
    </div>
</div>
