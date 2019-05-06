
<div class="card flex flex-col mt-4" style="height: 200px">
    <h3 class="font-normal text-xl py-4 mb-3 border-l-4 border-blue-light pl-4 -ml-5">
        Invite a user
    </h3>
    <div class="text-grey mb-4 flex-1">
        <form method="POST" class="text-right" action="{{ $project->path().'/invitations' }}">
            {{ csrf_field() }}
            <div class="mb-3">
                <input type="email" id="email" name="email" class="border border-grey rounded w-full" placeholder="Email address">
            </div>
            <button type="submit" class="button text-xs">Invite</button>
        </form>
    </div>

</div>
