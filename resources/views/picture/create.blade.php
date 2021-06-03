@extends('layouts.app')

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    @if ($message = Session::get('warning'))
        <div class="alert alert-warning">
            <p>{{ $message }}</p>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="validate_url d-none">

    </div>
    {!! Form::open(['route' => 'picture.download','method'=>'POST', 'id' => 'myForm']) !!}
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <div class="form-group">
                {!! Form::label('url', 'URL') !!}
                {!! Form::text('url', null, ['placeholder' => 'https://www.google.com.ua/?hl=ru','class' => 'form-control', 'required', 'id' => 'picture_url']) !!}
            </div>
        </div>
        <div class="col-xs-12 col-sm-6">
            <div class="form-group">
                {!! Form::label('lifetime', 'Lifetime (min)') !!}
                {!! Form::number('lifetime', null, ['placeholder' => '5', 'class' => 'form-control']) !!}
            </div>
        </div>

        <div class="col-12 text-left">
            <button type="button" id="mySubmit" class="btn btn-primary">Send</button>
        </div>
    </div>
    {!! Form::close() !!}
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
    <script>
        //Проверка на то, что по url находится картинка
        const checkImgSrc = src => {
            const img = new Image();
            img.onload = function () {
                $("#myForm").submit();
            }
            img.onerror = function () {
                $(".validate_url").removeClass('d-none');
                $(".validate_url").text('Wrong link!');
            }
            img.src = src;
        }

        $("#mySubmit").on('click', function () {
            checkImgSrc($("#picture_url").val());
        });
    </script>
@endsection
