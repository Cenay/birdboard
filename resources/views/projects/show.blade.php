@extends('layouts.app')

@section('content')

    <header class="flex items-center mb-3">
        <div class="flex items-end justify-between w-full">
            <p class="text-grey text-sm font-normal">
                <a href="/projects" class="text-grey text-sm font-normal no-underline">My Projects</a> // {{ $project->title }}
            </p>
            <a href="{{ $project->path() . '/edit' }}" class="button">Edit Project</a>
        </div>
    </header>

    <main>
        <div class="lg:flex -mx-2">
            <div class="lg:w-3/4 px-3 mb-6">

                <div class="mb-8">
                    <h2 class="text-grey font-normal text-lg mb-3">Tasks</h2>

                    {{-- tasks --}}
                    @foreach ($project->tasks as $task)

                        <div class="card mb-2">
                            <form method="POST" action="{{ $task->path() }}">
                                @method('PATCH')
                                @csrf
                                <div class="flex">
                                    <input value="{{ $task->body }}" name="body" class="w-full {{ $task->completed ? 'text-grey' : '' }}">
                                    <input name="completed" type="checkbox" onChange="this.form.submit()" {{ $task->completed ? 'checked' : '' }}>
                                </div>
                            </form>
                        </div>

                    @endforeach

                    <div class="card mb-2">
                        <form action="{{ $project->path() . '/tasks' }}" method="POST">
                            @csrf
                            <input name="body" placeholder="Add new task.." class="w-full">
                        </form>
                    </div>

                    {{-- <div class="card">{{ $task->body }}</div> --}}
                </div>

                <div>
                    <h2 class="text-grey font-normal text-lg mb-3">General Notes</h2>

                    {{-- notes --}}
                    <form method="POST" action="{{ $project->path() }}">
                        @csrf
                        @method('PATCH')
                        <textarea
                            name="notes"
                            class="card w-full min-h-200"
                            placeholder="Anything you want to make a note of?"
                            >{{ $project->notes }}
                        </textarea>
                        <button class="button mt-4" type="submit">Save Note</button>
                    </form>
                </div>

            </div>
            <div class="lg:w-1/4 px-3">
                @include ('projects.card')
            </div>
        </div>
    </main>

@endsection