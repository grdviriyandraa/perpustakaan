@if(session('success'))
    <div class="notice success" style="margin:0 0 18px">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="notice error" style="margin:0 0 18px">{{ session('error') }}</div>
@endif
