<flux:navlist.item icon="home" href="{{ route('parent.dashboard') ?? '#' }}" :current="request()->routeIs('parent.dashboard')">Accueil</flux:navlist.item>
<flux:navlist.item icon="folder" href="#">Dossiers Scolaires</flux:navlist.item>
<flux:navlist.item icon="star" href="#">Bulletins & Notes</flux:navlist.item>
<flux:navlist.item icon="calendar" href="#">Emploi du temps</flux:navlist.item>
<flux:navlist.item icon="currency-dollar" href="#">Scolarité & Paiements</flux:navlist.item>
