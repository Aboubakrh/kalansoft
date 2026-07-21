<flux:navlist.item icon="home" href="{{ route('parent.dashboard') ?? '#' }}" :current="request()->routeIs('parent.dashboard')">Accueil</flux:navlist.item>
<flux:navlist.item icon="folder" href="{{ route('parent.dossier-scolaire') }}" :current="request()->routeIs('parent.dossier-scolaire')">Dossiers Scolaires</flux:navlist.item>
<flux:navlist.item icon="star" href="{{ route('parent.bulletins-notes') }}" :current="request()->routeIs('parent.bulletins-notes')">Bulletins & Notes</flux:navlist.item>
<flux:navlist.item icon="calendar" href="{{ route('parent.emploi-du-temps') }}" :current="request()->routeIs('parent.emploi-du-temps')">Emploi du temps</flux:navlist.item>
<flux:navlist.item icon="currency-dollar" href="{{ route('parent.paiements') }}" :current="request()->routeIs('parent.paiements')">Scolarité & Paiements</flux:navlist.item>
