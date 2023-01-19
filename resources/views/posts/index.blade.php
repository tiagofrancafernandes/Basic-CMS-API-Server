<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ config('app.name', 'My Blog') }} :: Posts</title>
</head>

<body>
    <h1>Posts</h1>
    <div class="container">
        @if ($posts->count())
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Created at</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr
                        data-post-id="{{ $post->id }}"
                        data-post-slug="{{ $post->slug }}"
                        data-post-category="{{ $post->category ? $post->category->name : '' }}"
                        data-post-tags="{{ json_encode($post->tags ?? []) }}"
                    >
                        <td>{{ $post->title }}</td>
                        <td>
                            @if ($post->category)
                                <a href="#categories/{{ $post->category->slug }}">
                                    {{ $post->category->name }}
                                </a>
                            @endif
                        </td>
                        <td>{{ $post->created_at }}</td>
                        <td>
                            <a href="#show-more-{{ $post->slug }}">Show more...</a>
                            <button type="button">
                                Bookmark
                            </button>
                            <button type="button">
                                Save to see later
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <h5>No posts yet. Back later.</h5>
        @endif
    </div>
</body>

</html>
