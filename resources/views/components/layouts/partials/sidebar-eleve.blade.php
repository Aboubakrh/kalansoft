<flux:navlist.item icon="home" href="{{ route('eleve.dashboard') ?? '#' }}" :current="request()->routeIs('eleve.dashboard')">Accueil</flux:navlist.item>
<flux:navlist.item icon="star" href="{{ route('eleve.notes') }}" :current="request()->routeIs('eleve.notes')">Mes Notes</flux:navlist.item>
<flux:navlist.item icon="calendar" href="{{ route('eleve.emploi-du-temps') }}" :current="request()->routeIs('eleve.emploi-du-temps')">Mon Emploi du temps</flux:navlist.item>
<flux:navlist.item icon="book-open" href="{{ route('eleve.cahier-texte') }}" :current="request()->routeIs('eleve.cahier-texte')">Mes Devoirs</flux:navlist.item>
<flux:navlist.item icon="user-minus" href="{{ route('eleve.absences') }}" :current="request()->routeIs('eleve.absences')">Mes Absences</flux:navlist.item>
