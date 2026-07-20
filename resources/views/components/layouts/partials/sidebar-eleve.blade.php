<flux:navlist.item icon="home" href="{{ route('eleve.dashboard') ?? '#' }}" :current="request()->routeIs('eleve.dashboard')">Accueil</flux:navlist.item>
<flux:navlist.item icon="star" href="#">Mes Notes</flux:navlist.item>
<flux:navlist.item icon="calendar" href="#">Mon Emploi du temps</flux:navlist.item>
<flux:navlist.item icon="book-open" href="#">Mes Devoirs</flux:navlist.item>
<flux:navlist.item icon="user-minus" href="#">Mes Absences</flux:navlist.item>
