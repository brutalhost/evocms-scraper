<div class="row">
    <div class="col-lg-5">
        <form method="post" action="{{ route('scraper::tasks.create') }}" style="display: flex;flex-direction: column;">
            @csrf

            @include('scraper::parser.form')

            <button class="btn btn-success" type="submit">{{ __('scraper::global.submit') }}</button>
        </form>
    </div>
    <div class="col">
        @include('scraper::parser.helper')
    </div>
</div>
