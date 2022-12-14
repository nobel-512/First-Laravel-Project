@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-8">
    <div class="post-page">
      <div class="post-info">
        <h4 class="post-title">{{ $post->subject }}</h4>
        <div class="post-date">
          <span>Published at {{ $post->created_at }}</span>
          <span>By <a href="{{ route('users.show', $post->user->id) }}"><b>{{ $post->user->name }}</b></a></span>
        </div>
      </div>
      <img class="post-img" src="{{ asset($post->image->url) }}">
      <div class="post-body">
        <div class="ql-snow">
          <div class="ql-editor" style="height: inherit; outline: inherit; overflow: inherit; padding: inherit; white-space: inherit;">
            {!! $post->body !!}
          </div>
        </div>
      </div>
      <div class="post-categories mt-2" id="post-page-comments">
        @foreach ($post->categories as $category)
          <a href="{{ route('categories.show', $category->id) }}"><span class="badge rounded-pill post-category">{{ $category->title }}</span></a>
        @endforeach
      </div>
    </div>
    @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    @error('comment')
      <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @enderror
    @if (config('app.comments') == 1)
      <section class="post-page-comments">
        <div class="comments">
          <div class="post-page-comments-title">
            <h5>Comments ({{ Count($post->comments) }})</h5>
          </div>
          @guest
            <div class="alert alert-warning  mx-3 mt-4" role="alert">
              You must be logged in to comment
              @if (Route::has('login'))
                <a href="{{ route('login') }}" class="text-decoration-none">Login</a>
              @endif
              @if (Route::has('register'))
                <span> or </span>
                <a href="{{ route('register') }}" class="text-decoration-none">Register a new account</a>.
              @endif
            </div>
          @else
            <div class="add-comment">
              @if ( Route::currentRouteName() == 'comments.edit' )
                <form action="{{ route('comments.update', $edit_comment->id) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="col-auto">
                    <img class="rounded-circle" src="{{ asset(Auth::user()->image->url_md) }}" alt="">
                  </div>
                  <div class="form-floating col-6 col-md-7 col-xl-8">
                    <input type="text" class="form-control" id="comment" name="comment" placeholder="Edit your comment" value="{{ $edit_comment->comment }}" required>
                    <label for="comment">Edit your comment</label>
                  </div>
                  <div class="col-auto">
                    <button type="submit" class="btn btn-light add_comment_btn">Update</button>
                    <a href="{{ route('posts.show', $edit_comment->post_id) }}" class="btn btn-light add_comment_btn bg-secondary ms-1">Cancel</a>
                  </div>
                </form>
                <hr>
              @else
                <form action="{{ route('posts.comments.store', $post->id) }}" method="POST">
                  @csrf
                  <input type="hidden" name="post_id" value="{{ $post->id }}">
                  <div class="col-auto">
                    <img class="rounded-circle" src="{{ asset(Auth::user()->image->url_md) }}" alt="">
                  </div>
                  <div class="form-floating col-7 col-md-8 col-xl-9">
                    <input type="text" class="form-control" id="comment" name="comment" placeholder="Add comment" required>
                    <label for="comment">Add comment</label>
                  </div>
                  <div class="col-auto">
                    <button type="submit" class="btn btn-light add_comment_btn">Enter</button>
                  </div>
                </form>
              @endif
            </div>
          @endguest
          @foreach ($comments as $comment)
            <div class="row g-3 comment" id="comment_{{ $comment->id }}">
              <div class="col-auto">
                <img src="{{ asset($comment->user->image->url_md) }}" class="rounded-circle" alt="">
              </div>
              <div class="col-8 comment-info">
                <span>
                  <b>{{ $comment->user->name }}</b>
                  @if ($comment->user_id == $post->user_id)
                    <span class="badge rounded-pill bg-primary">Author</span>
                  @endif
                </span>
                <p>{{ $comment->comment }}</p>
                <span>Published at {{ $comment->created_at }}</span>
              </div>
              @if (Auth::check())
                @if ($comment->user->id == Auth::user()->id)
                  <div class="col-auto">
                    <form action="{{route('comments.destroy', $comment->id)}}" method="POST">
                      @csrf
                      @method('DELETE')
                      <a href="{{ route('comments.edit', $comment->id) }}" class="badge rounded-pill comment-edit"><i class="bi bi-pencil-square"></i> Edit</a>
                      <button class="badge rounded-pill comment-edit bg-danger" type="submit"><i class="bi bi-trash"></i> Delete</button>
                    </form>
                  </div>
                @endif
              @endif
            </div>
          @endforeach
        </div>
      </section>
    @endif
  </div>
  <div class="col-lg-4">
    <div class="sidebar-posts">
      <div class="sidebar-posts-title">
        <h5>Popular posts</h5>
      </div>
      @foreach ($popular_posts as $post)
        <div class="row g-0 bg-white position-relative sidebar-post">
          <div class="col-md-4 post-thumbnail" style="background-image: url({{ asset($post->image->url_md) }});"></div>
          <div class="col-md-8 p-3 p-md-2">
            <a class="stretched-link text-decoration-none" href="{{ route('posts.show', $post->id) }}"><h6 class="post-title"><b>{{ $post->subject }}</b></h6></a>
            <p class="m-0 text-muted">Published at {{ $post->created_at->format('Y-m-d') }}</p>
            <span class="text-muted">By <b>{{ $post->user->name }}</b></span>
          </div>
        </div>
      @endforeach
    </div>
    <div class="sidebar-posts">
      <div class="sidebar-posts-title">
        <h5>Latest posts</h5>
      </div>
      @foreach ($latest_posts as $post)
        <div class="row g-0 bg-white position-relative sidebar-post">
          <div class="col-md-4 post-thumbnail" style="background-image: url({{ asset($post->image->url_md) }});"></div>
          <div class="col-md-8 p-3 p-md-2">
            <a class="stretched-link text-decoration-none" href="{{ route('posts.show', $post->id) }}"><h6 class="post-title"><b>{{ $post->subject }}</b></h6></a>
            <p class="m-0 text-muted">Published at {{ $post->created_at->format('Y-m-d') }}</p>
            <span class="text-muted">By <b>{{ $post->user->name }}</b></span>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>
@endsection
