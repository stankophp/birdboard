<div class="field mb-6">
    <label for="title" class="label text-sm mb-2 block">
        Title
    </label>
    <div class="control">
        <input type="text" class="input bg-transparent border border-grey-light rounded w-full p-2 text-xs "
               name="title" id="title" value="{{ $project->title }}" required>
    </div>
</div>

<div class="field mb-6">
    <label for="description" class="label text-sm mb-2 block">
        Description
    </label>
    <div class="control">
                <textarea class="input bg-transparent border border-grey-light rounded w-full p-2 text-xs"
                          style="min-height: 150px" id="description" name="description" required
                          rows="3">{{ $project->description }}</textarea>
    </div>
</div>

<div class="field">
    <div class="control">
        <button type="submit" class="button is-link btn btn-primary btn-md">
            {{ $buttonText }}
        </button>
        <a href="{{ $project->path() }}">Cancel</a>
    </div>
</div>

@if($errors->any())
<div class="field mt-6">
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
</div>
@endif