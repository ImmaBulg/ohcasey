@inject('o', '\App\Ohcasey\Ohcasey')
<div>Ссылка на чехол:</div>
<a href="{{ action('OhcaseyController@goToShare', ['id' => $o->getShare()->share_hash]) }}" class="blue">{{ action('OhcaseyController@goToShare', ['id' => $o->getShare()->share_hash]) }}</a>