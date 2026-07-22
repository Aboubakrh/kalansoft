<flux:navlist.item icon="home" href="{{ route('professeur.dashboard') }}" :current="request()->routeIs('professeur.dashboard')">Vue d'ensemble</flux:navlist.item>
<flux:navlist.item icon="academic-cap" href="{{ route('professeur.classes') }}" :current="request()->routeIs('professeur.classes')">Mes Classes</flux:navlist.item>
<flux:navlist.item icon="calendar" href="{{ route('professeur.emploi-du-temps') }}" :current="request()->routeIs('professeur.emploi-du-temps')">Mon emploi du temps</flux:navlist.item>
<flux:navlist.item icon="document-text" href="{{ route('admin.evaluations.index') }}" :current="request()->routeIs('admin.evaluations.*') || request()->routeIs('admin.notes.*')">Saisie des Notes</flux:navlist.item>
<flux:navlist.item icon="book-open" href="{{ route('professeur.cahier-texte') }}" :current="request()->routeIs('professeur.cahier-texte')">Cahier de texte</flux:navlist.item>
<flux:navlist.item icon="user-minus" href="{{ route('professeur.pointages') }}" :current="request()->routeIs('professeur.pointages')">Mes Absences & Pointages</flux:navlist.item>
