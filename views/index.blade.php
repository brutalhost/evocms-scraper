@extends('scraper::layouts.app')

@section('buttons')
    <div id="actions">
        <div class="btn-group">
            <a href="javascript:;" class="btn btn-success" onclick="location.reload();">
                <i class="fa fa-refresh"></i><span>@lang('scraper::global.refresh')</span>
            </a>
        </div>
    </div>
@endsection

@section('body')
    @if(session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="tab-page" id="tab_main">
        <h2 class="tab">
            {{ __('scraper::global.parser') }}
        </h2>
        @include('scraper::parser.parser')
    </div>

    <div class="tab-page" id="tab_tasks">
        <h2 class="tab">
            {{ __('scraper::global.tasks_list') }}
        </h2>

        @include('scraper::tasks.tasks')
    </div>

    <script type="text/javascript">
        tpModule.addTabPage(document.getElementById("tab_main"));
        tpModule.addTabPage(document.getElementById("tab_tasks"));
    </script>
@endsection

