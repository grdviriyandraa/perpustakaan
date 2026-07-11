@php
$text = trim($text ?? $slot);
$lower = strtolower($text);
$class = 'cyan';
foreach (['tersedia', 'aktif', 'lunas', 'selesai', 'disetujui', 'terverifikasi', 'tepat', 'memenuhi', 'dibaca', 'populer'] as $key) {
    if (str_contains($lower, $key)) $class = 'green';
}
foreach (['menunggu', 'dipinjam', 'proses', 'belum'] as $key) {
    if (str_contains($lower, $key)) $class = 'orange';
}
foreach (['ditolak', 'terlambat', 'nonaktif', 'habis', 'belum bayar', 'belum dibaca'] as $key) {
    if (str_contains($lower, $key)) $class = 'red';
}
@endphp
<span class="badge {{ $class }}">{{ $text }}</span>
