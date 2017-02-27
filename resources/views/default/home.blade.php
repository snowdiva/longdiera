@extends('default.base')

@section('title', 'Welcome LongDiEra')

@section('content')
    <div class="mainbar">
        @foreach($list as $item)
        <div class="article">
            <h2><span>{{ $item->title }}</span></h2>
            <div class="clr"></div>
            <p class="bottom_line">Publis time:{{ date('Y-m-d', $item->publish_at) }}<a href="javascript:;"> {{ $item->author }} </a></p>
            {{--<img src="images/img_1.jpg" width="613" height="193" alt="image" />--}}
            <div class="clr"></div>
            <p>{{ $item->intro }}</p>
            <p><a href="{{ url('novel', ['id' => $item->id]) }}">Read more </a></p>
        </div>
        @endforeach
        <div class="article" style="padding:5px 20px 2px 20px; background:none; border:0;">
            {{ $list->links() }}
            {{--<p>Page 1 of 2 <span class="butons"><a href="#">3</a><a href="#">2</a> <a href="#" class="active">1</a></span></p>--}}
        </div>
    </div>
@endsection