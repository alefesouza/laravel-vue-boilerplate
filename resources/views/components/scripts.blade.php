<script>
var baseUrl = '{{ url('/data') }}/';
</script>
<script src="{{ js('app.js?v='.rand()) }}"></script>
@yield('scripts')
