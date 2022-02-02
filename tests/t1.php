<?php

require __DIR__.'/../autoload.php';


$config = $Lite->config->get('app');


$pubKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwv/GzbJ31PpqwvHoNlGdIcDWngJeEhyHZvTGbBvc10910G4MaKy650JLchBNTQPkCMNYJ/uJbHmegWYsZeRROHXdOKvSGaq5fKJhWbS7Dv5lZDyvQQFtrXaTtaYo2W6VHhIlHabF/vDi6PcdaDWV0hcNb5RptKLDS4F0dE7z2c2K+4gh3M35zLnUNJXiew6+AqpiiuQYa++aomVfS4Ou744FCV0AysnsRY4CD0XKrTa/4/kEboWbwvUd8DmlTkxXoJpwNNR/Nu/KSOHBi6kW/j88fFri5++GfWr422i94CtjXy9nwSb6tmyzfIVoOXrrTai3k7jOn+QZqHCGmdFRaQIDAQAB';
$priKey = 'MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDC/8bNsnfU+mrC8eg2UZ0hwNaeAl4SHIdm9MZsG9zXT3XQbgxorLrnQktyEE1NA+QIw1gn+4lseZ6BZixl5FE4dd04q9IZqrl8omFZtLsO/mVkPK9BAW2tdpO1pijZbpUeEiUdpsX+8OLo9x1oNZXSFw1vlGm0osNLgXR0TvPZzYr7iCHczfnMudQ0leJ7Dr4CqmKK5Bhr75qiZV9Lg67vjgUJXQDKyexFjgIPRcqtNr/j+QRuhZvC9R3wOaVOTFegmnA01H8278pI4cGLqRb+Pzx8WuLn74Z9avjbaL3gK2NfL2fBJvq2bLN8hWg5eutNqLeTuM6f5BmocIaZ0VFpAgMBAAECggEBAJTi5HE0HgjQtuln+OxYfkhCS9vTZO4hOEUlCceLsp/2/LaLABCAKije1moeBleSa+9A77N/fBsF9T9JuwaFQHqCi8l0b3PHhd6iwP/UXasCFHpnV0ykAZEbY4ajerchltuh8RLlvnF8jVRhMePaXi1OCqUyRU91ovWovzj6+3dFAGlMDmenpBeiEpDYTy5hYzJ1QI6ywMeouXlhTElhr2PS3aY8K1cYbYeTeAze5rbiAInGVPHy8fAFLKS06yv6MvqQqU+X1ifOC9PP5Usnma0En0hwbJ3mqZS6QnNVTpncdyPMP4xc4sLvphcmiS57UEe40pXfgb9QvoaavCPpgp0CgYEA/GkszNxlYBHH/yCQJfwNIjh9zFi3e2uvwPR6BHGd9dy1kV9NurOSzf4K1ZujsNhOvVCJeElHCNI6h0YIwJwWaVjCP+9ee328oVkdnpm8eVCBacFfnW/FCU/kQF6rs6ZuQuAtiWeSvl/zerkOBYmROhSJQD79TfPbAmBCwGWuGhMCgYEAxcWclphuOrrLopv0llpNFlL2tw0fXuAKo1TbHP7A09Pm5aIUYbncC4TX1yP3ilbEdO4bgMlYmtWjlrODvM1RiXiLreTF1KhxoiVTu2wcSFxmiZI2qv60kp32LAjPl0c2yR+h9koq2wGxq/D4VTOGWIBJPJ242W+JQ11kwI6xVhMCgYEAvXKfWn+NYybVamrhZnEg1m96E/b+eBciSfv03QL94Twv1xWl/JytcgjbzunLWX9w0ezx0SOGuls37LIm/ZHpzFX/LgeWba+49Y0yiwiuiotfJqYqAruSMuQQ2DN2QheHqJAj/X6MiHDyCUl9+bAAHYyuW1crvedqmQTw9QEcRJsCgYBmONfQ9wSykm5CpD1toUsK6OLghoXacg7NkUSX3g0o7/P+aSIDyR81TPqLFuoRtPtiPNg2XtvPW/FsKWlEIxOr7IS14vNmEZJ6brSywRR1Sl0takebZn9K8R6WcA9sb8CfgBwkwv0Xqe59otWYpEMiZ1xzWkp7CK14BkPXS2nZxQKBgAe8Wj9NKHJqtjTNm+kY1JW/MVfAxRvxW1F/wcWGP76ydaG/fRRozkzoIMnnCR7nH8GoDRbN0k2aUtCn+9bBw7S+e4IMKbpKgLJHfp8B4Ou8vKVUkpZR5El3L53D+LzgtJW4X5W0TYdY4XF/h5sRQ6RU8t1gg9VVirV6K57Txr04';

$unnrsa = new \LitePhp\LiRsa( $pubKey, $priKey );
$unnrsa->SetThirdPubKey($pubKey);
$a = '测试RSA2';
$sign = $unnrsa->sign( $a );
$y = $unnrsa->verifySign( $a, $sign, $pubKey );
var_dump( $sign, $y );

$arr = array('order'=>'20200826001','money'=>200);
$arr = $unnrsa->signArray($arr);
$y = $unnrsa->verifySignArray($arr);
var_dump($arr,$y);

$x = $unnrsa->encrypt( $a );
$y = $unnrsa->decrypt( $x );
var_dump( $x, $y );

$c = $unnrsa->createKey();
var_dump($c);





