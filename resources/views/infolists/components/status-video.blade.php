<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div>
        <video width="1080"  controls>
            <source src="{{asset($getState())}}" type="video/mp4">
            Your browser does not support the video tag.
        </video>

    </div>
</x-dynamic-component>
