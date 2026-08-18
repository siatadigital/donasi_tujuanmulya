@if( $user->is_verified == 0)
<p class="text-danger lead">Akun anda belum di validasi dan anda tidak bisa membuat galang dana. <a href="{{ URL::Route('user.getValidate',$auth['username']) }}" class="btn btn-primary">Validasi Sekarang.</a></p>
@elseif( $user->is_verified == 2 )
<p class="text-warning lead">Terima kasih telah melakukan validasi, kami akan segera mengkonfirmasi keaslian identitas anda.</p>
@elseif( $user->is_verified == 1 )
<p class="text-success lead">Akun anda telah valid, kini anda bisa membuat galang dana anda sendiri.</p>
@endif