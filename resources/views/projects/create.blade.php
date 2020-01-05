@extends('layouts.app')

@section('content')
    <form method="POST" action="/projects">
        <h1 class="heading is-1">Create a Project</h1>

        @csrf

        <div class="field">
            <div class="label" for="title">Title</label>
                <div class="control">
                    <input type="text" class="text" name="title" placeholder="Title">
                </div>
            </div>
        </div>

        <div class="field">
            <label class="label" for="description">Description</label>

            <div class="control">
                <textarea class="textarea" name="description" placeholder="Describe the project here"></textarea>
            </div>

        </div>


        <div class="field">
          <div class="control">
              <button type="submit" class="button is-link">Create Project</button>
              <a href="/projects">Cancel</a>
          </div>
        </div>


    </form>
@endsection