# Object oriented programming in PHP

## Classi in PHP

Le classi sono la base della programmazione orientata agli oggetti in PHP. Permettono di definire oggetti con proprietà (attributi) e comportamenti (metodi).

```
<?php

class Car
{
  // Proprietà protette, accessibili solo all'interno della classe o dalle classi derivate
  protected $color = '';
  protected $upholstery;

  // Costruttore: inizializza colore esterno e interno
  public function __construct($extColor = null, $intColor = null)
  {
    $this->color = $extColor;
    $this->upholstery = $intColor;
  }

  // Getter per il colore esterno
  public function getColor()
  {
    return $this->color;
  }

  // Setter per il colore esterno
  public function setColor(string $color)
  {
    $this->color = $color;
  }

  // Getter per l'interno (tappezzeria)
  public function getUpholstery()
  {
    return $this->upholstery;
  }

  // Setter per l'interno (tappezzeria)
  public function setUpholstery(string $upholstery)
  {
    $this->upholstery = $upholstery;
  }
}
```

- I metodi getColor() e getUpholstery() restituiscono i valori delle proprietà.
- I metodi setColor() e setUpholstery() permettono di modificare i valori delle proprietà.
- Il costruttore permette di inizializzare l'oggetto direttamente con i valori desiderati.

## Ereditarietà: estendere una classe
È possibile creare una classe che estende un'altra, ereditando tutte le sue proprietà e metodi.

```
class Truck extends Car
{
  protected $weight;

  // Costruttore con chiamata al costruttore della classe genitore
  public function __construct($extColor = null, $intColor = null, $weight = 0)
  {
    parent::__construct($extColor, $intColor); // Richiama il costruttore di Car
    $this->weight = $weight;
  }

  // Ridefinizione del metodo getColor()
  public function getColor()
  {
    return 'Truck color is: ' . parent::getColor();
  }

  // Setter per la proprietà weight
  public function setWeight($weight)
  {
    $this->weight = $weight;
  }
}
```

- Truck estende Car, quindi eredita tutte le proprietà e i metodi della classe base.
- Il metodo getColor() è stato ridefinito per personalizzare l'output (override).
- Il metodo parent::getColor() richiama la versione originale di Car.

### Utilizzo degli oggetti

```
$myCar = new Car("red", "black");
echo $myCar->getColor(); // Output: red

$myTruck = new Truck("blue", "grey", 5000);
echo $myTruck->getColor(); // Output: Truck color is: blue
```

## Interfacce in PHP

Le interfacce definiscono un contratto: specificano quali metodi una classe deve implementare, senza fornire un'implementazione concreta.

### Sintassi base

```
interface iCar {
  public function stop();
  public function start();
  public function changeGear(int $gear);
  public function park();
  public function accelerate();
}
```

- Tutti i metodi in un'interfaccia sono pubblici per definizione.
- Una classe che implementa un'interfaccia deve definire tutti i metodi dichiarati.

### Implementazione dell'interfaccia

```
class IntCar implements iCar
{
  public $isStopped = true; // Stato dell'auto: ferma o in movimento
  protected $gear = 3;      // Marcia corrente (default: 3)
  const maxGear = 5;        // Marcia massima
  protected $isParked = true; // Stato parcheggio

  public function stop()
  {
    $this->isStopped = true;
  }

  public function start()
  {
    $this->isStopped = false;
  }

  public function changeGear($gear)
  {
    // Limita la marcia entro i valori consentiti
    if ($gear > self::maxGear) {
      $gear = self::maxGear;
    }

    if ($gear < 0) {
      $gear = 0;
    }

    $this->gear = $gear;

    // Se la marcia è maggiore di 0, l'auto non è più parcheggiata
    if ($this->gear > 0) {
      $this->isParked = false;
    }
  }

  public function park()
  {
    $this->isStopped = true;
    $this->gear = 0;
    $this->isParked = true;
  }

  public function accelerate()
  {
    // Da implementare: esempio lasciato vuoto
  }
}
```

### Esempio d'uso

```
$auto = new IntCar();
$auto->start();         // L'auto si avvia
$auto->changeGear(3);   // Cambia alla terza marcia
var_dump($auto);        // Mostra lo stato interno dell'oggetto
```

### Considerazioni

- Le interfacce sono utili per definire comportamenti comuni tra classi diverse.
- IntCar può essere trattata ovunque venga richiesto un oggetto iCar, favorendo il polimorfismo.
- L'uso della costante maxGear mostra un buon esempio di vincolo interno alla classe.

## Classi astratte, `final` e metodi `static` in PHP

```
function incrementCounter()
{
  static $counter = 0;
  echo ++$counter . PHP_EOL;
}
```

- Una variabile static dentro una funzione mantiene il suo valore tra un'esecuzione e l'altra.
- Utile per contatori o cache locali.

### Classi astratte

```
abstract class AbsCar
{
  protected $speed = 0;
  public static $classVersion = '1';

  // Metodo astratto: deve essere implementato nella classe figlia
  abstract protected function speedUp(int $speed);

  // Metodo finale: non può essere sovrascritto
  public final function getSpeed()
  {
    return $this->speed;
  }

  public function getVersion()
  {
    return self::$classVersion;
  }
}
```

- Le classi astratte non possono essere istanziate direttamente.
- Utili per definire un modello base comune.
- Il metodo `getSpeed()` è dichiarato final, quindi non può essere ridefinito dalle sottoclassi.
- Estendere una classe astratta

```
class AbsCarExt extends AbsCar
{
  public const SPEEDUNIT = 'm/s';
  public const MAXAGE = 2020 + 4;

  public function speedUp(int $speed)
  {
    $this->speed += $speed;
  }

  public function getParentVersion()
  {
    return parent::$classVersion;
  }
}
```

- `AbsCarExt` implementa il metodo astratto `speedUp()`.
- `SPEEDUNIT` e `MAXAGE` sono costanti di classe.
- `parent::$classVersion` accede alla variabile statica definita nella classe genitore.

```
$car = new AbsCarExt();
$car->speedUp(5);
echo $car->getSpeed();           // Output: 5
echo $car->getVersion();         // Output: 1
echo $car::MAXAGE;               // Output: 2024
```

### `self` vs `static` vs `parent`
| Comando | Significato |
|---------|-------------|
`self::` | Riferimento alla classe attuale, dove è definito il metodo
`static::` |Riferimento alla classe che ha chiamato il metodo (late static binding)
`parent::` |Riferimento alla superclasse diretta


### Esempio pratico: `new self()` vs `new static()`

```
class Person
{
  public $name;

  public function __construct($name)
  {
    $this->name = $name;
  }

  public static function createSelf($name)
  {
    return new self($name); // Sempre istanza di Person
  }

  public static function createStatic($name)
  {
    return new static($name); // Può diventare Employee se chiamato da Employee
  }
}

class Employee extends Person
{
  public $position;

  public function __construct($name, $position = "Staff")
  {
    parent::__construct($name);
    $this->position = $position;
  }
}

$person = Person::createSelf("Mario");
$employee1 = Employee::createSelf("Lucia");     // ❌ Crea un Person
$employee2 = Employee::createStatic("Giovanni"); // ✅ Crea un Employee

echo get_class($person) . "\n";     // Person
echo get_class($employee1) . "\n";  // Person ❌
echo get_class($employee2) . "\n";  // Employee ✅
```

### Spiegazione finale:

- `new self()` ignora la classe che ha chiamato il metodo e istanzia sempre la classe dove il metodo è definito.
- `new static()` rispetta la classe da cui è partito il metodo e permette un comportamento più dinamico.

## Tipizzazione in PHP

PHP supporta la tipizzazione forte a partire da PHP 7 e migliora con PHP 8, permettendo di definire i tipi per proprietà, parametri e valori di ritorno.

### Esempio di classe con tipizzazione

```
class Type {
  // 1. Tipi primitivi
  public int $age;
  public int|float $height;
  public string $name;
  public bool $isActive;

  // 2. Tipi array
  public ?array $tags;
  public array $scores;

  // 3. Tipi oggetto
  public DateTime $createdAt;

  // 4. Unione di tipi
  public int|string $id;

  // 5. Tipi nullabili
  public ?string $nickname = null;

  // 6. mixed
  public mixed $anything;

  // 7. static/self/parent in metodi
  public static function create(): static
  {
    return new static();
  }
}
```

| Simbolo | Significato |
|---------|-------------|
`?` | Indica tipo nullabile (?string = stringa o null) |
`??` | Operatore di coalescenza: A ?? B = A se non null, altrimenti B |
`:` | tipo	Specifica il tipo di ritorno del metodo |

## Constructor Property Promotion (PHP 8)

Introdotto in PHP 8, il Constructor Property Promotion permette di scrivere meno codice quando si definiscono classi che hanno solo proprietà inizializzate dal costruttore.

### Prima (Metodo Tradizionale)

```
class PersonOld
{
    public string $name;
    public int $age;

    public function __construct(string $name, int $age)
    {
        $this->name = $name;
        $this->age = $age;
    }
}
```

Dobbiamo dichiarare le proprietà della classe e poi assegnarle nel costruttore → due passaggi ripetitivi.

### Dopo (Constructor Property Promotion)

```
class PersonNew
{
    public function __construct(
        public string $name,
        public int $age
    ) {}
}
```

- Tutto in una sola riga per proprietà.
- PHP crea automaticamente le proprietà e le assegna.

### Esempio d’uso

```
$old = new PersonOld("Mario", 30);
$new = new PersonNew("Luca", 25);

var_dump($old);
var_dump($new);
```

- Funziona solo nel costruttore `(__construct)`.
- Le proprietà devono essere visibili pubblicamente, o comunque avere un livello di visibilità (public, protected, private).
- È una funzionalità di comodità sintattica, non cambia il comportamento del codice.

## Readonly: Proprietà e Classi (PHP 8.1+)

Le proprietà readonly impediscono la modifica del loro valore dopo l'inizializzazione. Questo consente di creare oggetti immutabili, migliorando la sicurezza e la chiarezza del codice.

### Proprietà `readonly`

```
class Product {
    public readonly string $code;

    public function __construct(string $code) {
        $this->code = $code;
    }
}

$product = new Product("ABC123");
echo $product->code;        // OK
$product->code = "XYZ456";  // ❌ Errore: Cannot modify readonly property
```

- La proprietà può essere scritta una sola volta, di solito nel costruttore.
- Dopo l’inizializzazione, ogni tentativo di modifica genera un errore a runtime.

### Classi `readonly`

```
readonly class User {
    public function __construct(
        public string $username,
        public string $email
    ) {}
}

$user = new User("marco", "marco@example.com");
echo $user->username;       // OK
$user->email = "new@example.com"; // ❌ Errore: Cannot modify readonly property
```

- Tutte le proprietà della classe sono automaticamente readonly.
- Non è possibile modificare nessuna proprietà dopo la creazione dell'oggetto.

### Caratteristiche principali
| Caratteristica | Supportato |
|-|-|
| Scrittura solo nel costruttore | ✅ |
| Modifica dopo l’inizializzazione | ❌ |
| Uso con promoted properties | ✅ |
| Uso con proprietà statiche | ❌ |
| Uso con proprietà non pubbliche | ❌ (per le classi readonly) |

### Quando usarle
- Per oggetti immutabili, cioè che non devono cambiare dopo la creazione.
- Per rappresentare Value Objects, come:
  - Coordinate geografiche
  - Date e orari
  - Identificatori (ID)
  - Configurazioni

## Nullsafe Operator (`?->`) – PHP 8

L'operatore `?->` consente di accedere in sicurezza a proprietà o metodi di un oggetto solo se non è `null`. Se uno degli elementi nella catena è `null`, l'intera espressione restituisce `null` senza generare un errore.

### Prima di PHP 8: Controlli manuali

```
if ($user !== null && $user->profile !== null) {
    $user->profile->getAvatar();
}
```

Bisognava fare controlli espliciti per evitare errori nel caso in cui `$user` o `$user->profile` fossero `null`.

### Con il nullsafe operator

```
$user?->profile?->getAvatar();
```

- L’espressione viene valutata solo se ogni parte è non nulla.
- Se $user è null, o $user->profile è null, il risultato sarà null senza errori.

### Esempio completo

```
class Profile {
    public function getAvatar(): string {
        return "avatar.png";
    }
}

class User {
    public ?Profile $profile = null;
}

$user = new User();
$avatar = $user?->profile?->getAvatar(); // restituisce null
```

- Codice più pulito e leggibile.
- Elimina la necessità di controlli annidati per oggetti null.

## Traits – Riutilizzo del Codice (PHP)

I traits sono un meccanismo per riutilizzare metodi in più classi, senza usare l’ereditarietà. Sono utili quando vuoi condividere funzionalità comuni tra classi non correlate.

### Definizione di un Trait

```
trait Logger {
    public function log($message) {
        echo "[LOG] " . $message . "\n";
    }
}
```

I traits possono contenere metodi (e anche proprietà) che verranno "iniettati" nelle classi che li usano.

### Uso del Trait in più classi

```
class TraitOne {
    use Logger;
}

class TraitTwo {
    use Logger;
}
```
Le classi TraitOne e TraitTwo ereditano il metodo log() dal trait Logger, anche se non c'è relazione tra loro.

### Esempio d’uso

```
$user = new TraitOne();
$user->log("Utente registrato");       // [LOG] Utente registrato

$product = new TraitTwo();
$product->log("Nuovo prodotto aggiunto"); // [LOG] Nuovo prodotto aggiunto
```

### Caratteristiche dei Traits
| Caratteristica | Supportato |
|-|-|
|Possibilità di usare più traits |	✅ |
|Può contenere metodi e proprietà |	✅ |
|Risolve conflitti tra traits |	✅ (con insteadof e as) |
|È alternativa all’ereditarietà |	✅ |

### Quando usare un Trait

- Quando vuoi condividere comportamenti comuni tra classi non collegate da una gerarchia.
- Per evitare duplicazione di codice in classi diverse.
- Per comporre funzionalità senza vincoli di ereditarietà singola.

## Magic Methods in PHP

- I metodi magici sono funzioni speciali che iniziano con __ (doppio underscore) e vengono invocati automaticamente da PHP in situazioni specifiche.
- Alcuni sono particolarmente importanti nel contesto del corso (indicati con ***).

### `__construct()` *** – Costruttore 
Chiamato automaticamente alla creazione di un oggetto.

```
class MagicConstruct {
    public function __construct() {
        echo "Oggetto creato!\n";
    }
}

$u = new MagicConstruct(); // Output: Oggetto creato!
```

### `__destruct()` – Distruttore
Eseguito quando l’oggetto viene distrutto o lo script termina.

```
class Test {
    public function __destruct() {
        echo "Oggetto distrutto\n";
    }
}

$t = new Test(); // Alla fine dello script: Oggetto distrutto
```

### `__toString()` – Oggetto come stringa
Viene chiamato quando si tenta di stampare un oggetto come stringa.

```
class MagicToString {
    public function __toString() {
        return "Libro stampato come stringa\n";
    }
}

echo new MagicToString(); // Output: Libro stampato come stringa
```

### `__get($name)` *** – Accesso a proprietà non definite

```
class MagicGet {
    public function __get($name) {
        return "Hai cercato '$name', ma non esiste!\n";
    }
}

$obj = new MagicGet();
echo $obj->pippo; // Output: Hai cercato 'pippo', ma non esiste!
```

### `__set($name, $value)` – Assegnazione a proprietà non definite

```
class MagicSet {
    public function __set($name, $value) {
        echo "Hai provato a settare '$name' = '$value'\n";
    }
}

$obj = new MagicSet();
$obj->pippo = 123; // Output: Hai provato a settare 'pippo' = '123'
```

### `__call($name, $args)` *** – Chiamata a metodo non definito (istanza)

```
class MagicCall {
    public function __call($name, $args) {
        echo "Metodo '$name' chiamato con argomenti: " . implode(", ", $args) . "\n";
    }
}

$m = new MagicCall();
$m->saluta("Mario"); // Output: Metodo 'saluta' chiamato con argomenti: Mario
```

### `__callStatic($name, $args)` *** – Chiamata a metodo statico non definito

```
class MagicCallStatic {
    public static function __callStatic($name, $args) {
        echo "Metodo statico '$name' chiamato con " . implode(", ", $args) . "\n";
    }
}

MagicCallStatic::pippo(1, 2); // Output: Metodo statico 'pippo' chiamato con 1, 2
```

#### Esempio applicativo:

```
class MathOps {
    public static function __callStatic($method, $args) {
        switch ($method) {
            case 'square':
                return $args[0] ** 2;
            case 'cube':
                return $args[0] ** 3;
            default:
                throw new Exception("Metodo statico '$method' non definito.");
        }
    }
}

echo MathOps::square(4); // 16
echo MathOps::cube(2);   // 8
```

### `__clone()` – Clonazione dell'oggetto
Invocato quando un oggetto viene clonato con clone.

```
class MagicClone {
    public function __clone() {
        echo "Clonazione avvenuta\n";
    }
}

$p1 = new MagicClone();
$p2 = clone $p1; // Output: Clonazione avvenuta
```

### Altri metodi magici (non trattati nel corso, ma esistono)
`__isset()`, `__unset()` – Intercettano `isset()` e `unset()` su proprietà non definite

`__sleep()`, `__wakeup()` – Usati nella serializzazione

`__serialize()`, `__unserialize()` – Nuove versioni per la serializzazione personalizzata (PHP 7.4+)

`__debugInfo()` – Definisce il contenuto mostrato da `var_dump()`

## Passaggio di Parametri per Valore e per Riferimento in PHP

In PHP, i dati possono essere passati alle funzioni per valore (comportamento predefinito) o per riferimento (esplicitamente, con &). Il comportamento varia anche in base al tipo di dato (oggetto, array, valore scalare).

### Esempio base

```
$data = [
    'name' => 'John',
    'lastname' => 'Dow'
];

$obj = new stdClass();
$obj->name = 'John';
$obj->lastname = 'Dow';

$name = 'John Dow';

function modifyVal($val) {
    if (is_object($val)) {
        $val->name = 'Vito';
    } else if (is_array($val)) {
        $val['name'] = 'Vito';
    } else {
        $val = 'Vito';
    }

    echo "Dentro la funzione:\n";
    var_dump($val);
}

echo "Prima della funzione:\n";
var_dump($data);
modifyVal($data);
echo "Dopo la funzione:\n";
var_dump($data);
```

### Risultato spiegato
| Tipo | Cosa succede | Motivo |
|-|-|-|
|Array |	Modifica non persiste |	Gli array vengono copiati quando passati per valore
|Oggetto |	Modifica persiste |	Gli oggetti vengono passati come riferimento implicito
|Valore |	Modifica non persiste |	Le variabili scalari sono passate per copia

### Passaggio per riferimento con `&`
Per forzare la modifica diretta della variabile originale, si usa & davanti al parametro nella funzione.

```
function modifyRef(&$val) {
    $val = 'Modificato';
}

$nome = 'Mario';
modifyRef($nome);
echo $nome; // Output: Modificato
```

### Riepilogo
- Le variabili scalari (stringhe, interi, booleani, ecc.) sono passate per valore, quindi la funzione lavora su una copia.
- Gli oggetti sono gestiti tramite un handler interno, quindi si comportano come se fossero passati per riferimento implicito.
- Gli array, se non passati con &, vengono copiati, quindi le modifiche dentro la funzione non si riflettono all’esterno.
- Aggiungere & permette di modificare direttamente la variabile originale, indipendentemente dal tipo.

## Namespaces in PHP

I namespace permettono di organizzare il codice ed evitare conflitti tra classi, funzioni o costanti con lo stesso nome, soprattutto in progetti di grandi dimensioni o che integrano librerie esterne.

### Dichiarazione di un namespace

- Va dichiarato all'inizio del file, prima di qualsiasi codice PHP (eccetto declare).
- Convenzione: una classe per file, con il nome del file corrispondente al nome della classe.

```
// File: Namespace1/Test.php
<?php
namespace Namespace1;

class Test {
    public function __construct() {
        echo __CLASS__ . " creato\n";
    }
}
```

### Esempio di conflitto risolto con namespace
Supponiamo di avere un’altra classe Test nello spazio globale:

```
// File principale, es. index.php
<?php

class Test {
    public function __construct() {
        echo __CLASS__ . " creato\n";
    }
}

$test = new Test(); // Usa la classe globale

include_once 'Namespace1/Test.php'; // Importa la classe con namespace

$namespaceTest = new Namespace1\Test(); // Usa la classe del namespace
```

### Osservazioni
- Se non usi include/require per caricare i file, PHP non troverà la classe.
- In progetti strutturati si usa un autoloader (es. Composer) per caricare le classi automaticamente.

### Vantaggi dei namespace
| Vantaggio | Descrizione |
|-|-|
| Evita conflitti | Due classi con lo stesso nome possono coesistere in namespace diversi |
| Organizzazione del codice | Mantiene il codice modulare e leggibile |
| Compatibilità con librerie esterne | Le librerie possono dichiarare classi senza preoccuparsi di nomi duplicati |

### Best practice

- La struttura delle cartelle dovrebbe rispecchiare i namespace.
- I nomi dei file dovrebbero corrispondere al nome della classe.
- Usa PSR-4 per l'autoloading se usi Composer.

## Autoloading delle Classi con `spl_autoload_register`

L'autoloading permette di caricare automaticamente le classi quando vengono istanziate, evitando di scrivere manualmente tutti gli include o require.

### Funzionamento base
```
function autoloadClass($className) {
    echo "Caricamento della classe: $className\n";
}

spl_autoload_register('autoloadClass');

$class1 = new Namespace\Test(); // PHP chiama autoloadClass('Namespace\Test')
$class2 = new Test();           // PHP chiama autoloadClass('Test')
```

- PHP, quando non trova una classe definita, invoca automaticamente ogni funzione registrata con spl_autoload_register.
- Se una delle funzioni riesce a caricare il file corretto, il programma continua normalmente.

### Esempio pratico di autoload con inclusione file
Struttura del progetto

```
/progetto
│
├── index.php
└── classi/
    ├── Persona.php
    └── Cliente.php
classi/Persona.php
```

`classi/Persona.php`
```
<?php

class Persona {
    public function saluta() {
        echo "Ciao!\n";
    }
}
```
`index.php`

```
<?php

spl_autoload_register(function ($className) {
    require_once __DIR__ . '/classi/' . $className . '.php';
});

$utente = new Persona();  // Autoload di classi/Persona.php
$utente->saluta();        // Output: Ciao!
```

### Come funziona
1. PHP prova a istanziare una classe (new Persona()).
1. Non trovandola, chiama la funzione anonima registrata tramite spl_autoload_register().
1. La funzione costruisce il percorso del file in base al nome della classe.
1. Se il file esiste e viene incluso correttamente, la classe è definita e il codice prosegue.

### Vantaggi dell'autoloading
| Vantaggio | Descrizione |
|-|-|
|Meno require manuali |	Non serve includere ogni file a mano|
|Più pulizia e organizzazione |	Struttura modulare e facilmente estendibile|
|Compatibilità con standard |	Funziona con lo standard PSR-4 (se si usa Composer)|

### Best practice
- La struttura delle cartelle dovrebbe riflettere il nome delle classi.
- Se possibile, usa Composer e il suo autoloader PSR-4 per gestire namespace e percorsi automaticamente.

# Installazione e Utilizzo di Librerie PHP con Composer

## Cosa è Composer?
Composer è il gestore di pacchetti standard per PHP. Serve per installare e aggiornare librerie, gestire le dipendenze, e caricarle automaticamente nel progetto.

## Inizializzare Composer nel progetto
Esegui nel terminale, nella cartella del tuo progetto:

```
composer init
```

Rispondi alle domande (puoi lasciare vuoto o usare i default). Questo comando crea il file composer.json.

## Installare dipendenze
Nel terminale, esegui:

```
composer require fakerphp/faker
```

Questo:
1. Scarica la libreria fakerphp/faker
1. Aggiunge la libreria nel file composer.json
1. Crea il file composer.lock e la cartella vendor/

## Struttura del progetto dopo l’installazione
```
/tuo-progetto
│
├── index.php
├── composer.json
├── composer.lock
└── /vendor
    └── autoload.php
    └── fakerphp/
```

## Utilizzo della libreria nel codice
```
<?php

require_once __DIR__ . '/vendor/autoload.php'; // carica automaticamente tutte le classi

$faker = Faker\Factory::create();

echo $faker->name();      // es. "Giovanni Rossi"
echo "\n";
echo $faker->email();     // es. "giovanni.rossi@example.com"
```

## Spiegazione tecnica
- `require_once 'vendor/autoload.php'`: carica automaticamente tutte le classi delle librerie installate via Composer.
- `Faker\Factory::create()`: istanzia il generatore Faker.
- I metodi come `name()`, `email()` generano dati casuali realistici (nome, email, indirizzo, ecc.).

## Suggerimenti e buone pratiche
Non modificare mai la cartella `/vendor` manualmente.

Per rimuovere una libreria:

```
composer remove fakerphp/faker
```

Per aggiornare tutto:

```
composer update
```

## Requisiti
PHP installato (versione compatibile con la libreria)

Composer installato globalmente:
https://getcomposer.org/download/

# Gestione degli errori e delle eccezzioni

## Uso di `try`, `catch` e `finally`

La gestione delle eccezioni permette di intercettare gli errori in modo controllato, migliorando la stabilità del codice e la qualità del feedback.

### Esempio senza eccezioni (gestione base)
```
<?php

function getFileContent($filename = '') {
    if (!file_exists($filename)) {
        return false;
    }
    return file_get_contents($filename);
}

$content = getFileContent('file.txt');
if ($content === false) {
    echo "Errore: file non trovato.";
}
```

- Non è chiaro cosa ha causato l'errore.
- Non restituisce informazioni dettagliate.

### Esempio con eccezioni (throw, try, catch)
```
<?php

function getFileContent($filename = '') {
    if (!file_exists($filename)) {
        // Lancia un'eccezione se il file non esiste
        throw new Exception("$filename non esiste", -20);
    }
    return file_get_contents($filename);
}

try {
    $content = getFileContent('file.txt');
    echo $content;
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage(),
        'code'    => $e->getCode()
    ];
    
    echo json_encode($response);
}
```

- `throw new Exception(...)`: lancia un'eccezione.
- `try { ... } catch { ... }`: blocco per intercettare l'errore e gestirlo.
- `$e->getMessage()`: messaggio descrittivo.
- `$e->getCode()`: codice dell’eccezione (può essere personalizzato).

### Uso di finally (facoltativo)
Il blocco finally viene eseguito sempre, sia in caso di successo che di errore.

```
try {
    $content = getFileContent('file.txt');
    echo $content;
} catch (Exception $e) {
    echo "Errore: " . $e->getMessage();
} finally {
    echo "\nOperazione terminata.";
}
```

### Quando usare le eccezioni
- Per segnalare condizioni di errore critiche che richiedono un comportamento alternativo.
- In alternativa al return false per evitare controlli ripetitivi e poco espliciti.

## Custom Exception

Le eccezioni personalizzate ti permettono di creare tipi specifici di errore, migliorando la gestione dei casi particolari e rendendo il codice più leggibile e manutenibile.

### Creare una classe di eccezione personalizzata
Una custom exception si definisce estendendo la classe Exception.

```
<?php

class FileMissingException extends Exception {}
```

Puoi anche ridefinire il costruttore o aggiungere metodi personalizzati se necessario, ma non è obbligatorio.

### Esempio completo con `try`, `catch` e `custom exception`
```
<?php

// Eccezione personalizzata
class FileMissingException extends Exception {}

function getFileContent($filename = '') {
    if (!file_exists($filename)) {
        // Lancia l'eccezione personalizzata
        throw new FileMissingException("Il file '$filename' non esiste", 404);
    }
    return file_get_contents($filename);
}

try {
    $content = getFileContent('file.txt');
    echo $content;
} catch (FileMissingException $e) {
    echo "Errore di file: " . $e->getMessage() . " (codice: " . $e->getCode() . ")";
} catch (Exception $e) {
    echo "Errore generico: " . $e->getMessage();
}
```

Vantaggi delle eccezioni personalizzate
| Vantaggio | Descrizione |
|-|-|
| Maggiore chiarezza semantica | È più chiaro che tipo di errore si è verificato |
| Migliore controllo | Puoi intercettare solo eccezioni specifiche in blocchi catch dedicati |
| Estendibilità | Puoi aggiungere metodi o proprietà utili per quel tipo di errore |

### Gerarchia di eccezioni
Puoi anche creare una gerarchia per tipi di errore diversi:

```
class AppException extends Exception {}
class FileMissingException extends AppException {}
class PermissionDeniedException extends AppException {}
```

In questo modo, puoi catturare errori specifici o tutti gli errori della tua applicazione con un unico `catch (AppException $e)`.

## Gestione degli Errori a Runtime con set_error_handler()
In PHP, `set_error_handler()` permette di intercettare e gestire gli errori a runtime in modo personalizzato, evitando che vengano mostrati direttamente all’utente o generino comportamenti indesiderati.

### Sintassi

```
set_error_handler(callable $handler, int $error_levels = E_ALL);
```

- $handler: funzione personalizzata da eseguire quando si verifica un errore.
- $error_levels: livello (o combinazione) di errori da intercettare (E_WARNING, E_NOTICE, E_ALL, ecc.).

### Esempio base
```
<?php

function customErrorHandler($errno, $errstr, $errfile, $errline) {
    echo "⚠️ Errore [$errno]: $errstr in $errfile sulla linea $errline\n";
    return true; // blocca l'handler di default di PHP
}

set_error_handler('customErrorHandler');

// Esempio: divisione per zero (genera E_WARNING)
echo 10 / 0;
```

### Parametri della funzione handler
| Parametro | Descrizione |
|-|-|
|`$errno` |	Codice dell’errore (es. E_WARNING) |
|`$errstr` |	Messaggio dell’errore |
|`$errfile` |	File in cui si è verificato |
|`$errline` |	Riga del file |

### Restituire `true` o `false`
- return **true**: segnala a PHP che l’errore è stato gestito, e blocca il gestore predefinito.
- return **false**: l’errore verrà comunque passato all’handler standard di PHP.

### Esempio con log e filtro livelli
```
<?php

function customErrorHandler($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        return false; // ignora l'errore se non incluso in error_reporting
    }

    error_log("[$errno] $errstr in $errfile on line $errline", 3, "error_log.txt");
    echo "Errore catturato: controlla il file di log.\n";
    return true;
}

set_error_handler('customErrorHandler', E_ALL);

// Provoca un warning
include('file_non_esistente.php');
```

### Disattivare il gestore personalizzato
```
restore_error_handler();
```

### Quando usare `set_error_handler()`
- Per intercettare warning o notice senza fermare lo script.
- Per log personalizzati o invio email in caso di errori.
- Per gestire ambiente di produzione evitando che gli errori vengano mostrati direttamente.

### Differenza tra errori ed eccezioni

| Aspetto |	`set_error_handler()` | `try/catch` con `Exception` |
|-|-|-|
| Tipo |	Errori di esecuzione (es. warning, notice)	| Errori lanciati intenzionalmente |
| Gestione globale |	✅	| ❌ (gestione locale nel blocco try) |
| Blocco script |	❌ (non blocca lo script)	| ✅ se non gestita |

## Log degli Errori ed Eccezioni con Sentry (PHP)
Sentry è una piattaforma di monitoraggio degli errori in tempo reale, che consente di raccogliere, analizzare e risolvere problemi in applicazioni PHP (e non solo).

### Installazione di Sentry via Composer
Nel terminale:

```
composer require sentry/sentry
```

### Inizializzare Sentry nel tuo progetto
Nel punto di avvio del progetto (es. index.php o bootstrap.php):

```
<?php

require_once __DIR__ . '/vendor/autoload.php';

Sentry\init([
    'dsn' => 'https://<PUBLIC_KEY>@o<ORG_ID>.ingest.sentry.io/<PROJECT_ID>',
    'environment' => 'production',
    'release' => '1.0.0',
]);
```

Sostituisci la DSN con quella fornita dal tuo progetto Sentry.

### Catturare eccezioni
Manualmente (es. in un catch)

```
try {
    throw new Exception("Errore personalizzato!");
} catch (Exception $e) {
    Sentry\captureException($e);
}
```

### Catturare eccezioni globali (uncaught)
Per intercettare eccezioni non gestite, puoi usare un handler globale:

```
set_exception_handler(function ($exception) {
    Sentry\captureException($exception);
    http_response_code(500);
    echo "Errore interno. Riprova più tardi.";
});
```

### Catturare errori PHP (warning, notice, fatal)

```
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    Sentry\captureMessage("Errore [$errno]: $errstr in $errfile alla linea $errline");
    return true;
});
```

Gli errori fatali `(E_ERROR)` non possono essere gestiti con `set_error_handler()` ma puoi usare `register_shutdown_function()` come workaround.

### Log personalizzati
```
Sentry\captureMessage("Login fallito per utente admin");
```

### Inviare informazioni aggiuntive (context)
```
Sentry\configureScope(function (Sentry\State\Scope $scope): void {
    $scope->setUser([
        'id' => '123',
        'email' => 'utente@example.com',
    ]);

    $scope->setTag('controller', 'auth');
    $scope->setExtra('input_data', $_POST);
});
```

### Best practice
|Consiglio | Dettaglio |
|-|-|
| Inizializzare il prima possibile |	Meglio nel file di bootstrap o index.php |
| Disattivare in locale |	Usa environment per ignorare errori in development |
| Usare release |	Associa errori a versioni specifiche dell’app |
| Aggiungere contesto |	Utente, dati di input, ID di sessione, ecc. |
| Combinare con altri sistemi |	Puoi loggare anche su file o syslog oltre che su Sentry |

## Gestione di Eccezioni Non Catturate con `set_exception_handler()`
`set_exception_handler()` consente di definire un gestore globale per tutte le eccezioni non intercettate `(uncaught exceptions)` nel tuo codice PHP.
È utile per evitare interruzioni improvvise e per loggare gli errori in modo centralizzato (es. con Sentry o file di log).

### Sintassi
```
set_exception_handler(callable $handler);
```

Il gestore deve accettare un solo parametro, di tipo `Throwable` (o `Exception` per PHP < 7).

### Esempio base
```
<?php

set_exception_handler(function ($exception) {
    echo "Eccezione non catturata: " . $exception->getMessage();
    // log, notifiche, ecc.
});

function divide($a, $b) {
    if ($b === 0) {
        throw new Exception("Divisione per zero non permessa");
    }
    return $a / $b;
}

echo divide(10, 0); // Lancia eccezione → catturata da set_exception_handler()
```

### Gestione avanzata con risposta JSON (es. per API)
```
<?php

set_exception_handler(function ($e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'code'  => $e->getCode()
    ]);
});
```

### Integrazione con sistemi di logging (es. Sentry)
```
set_exception_handler(function ($e) {
    Sentry\captureException($e);
    http_response_code(500);
    echo "Errore interno del server.";
});
```

### Differenza con try/catch
|Aspetto | try/catch | set_exception_handler() |
|-|-|-|
|Ambito	| Locale (solo blocco try)	| Globale (tutto lo script)|
|Obbligo di chiamata	| Esplicito	| Automatico alla prima eccezione non gestita|
|Personalizzazione	| Specifica per tipo di errore	| Generica, usata come fallback|

### Considerazioni importanti
Il gestore non può riprendere l’esecuzione: una volta chiamato, l’esecuzione termina.

Serve solo per eccezioni non gestite.

È buona pratica definire il gestore all’inizio dello script o nel file di bootstrap.

### Rimuovere il gestore (opzionale)
php
Copia codice
restore_exception_handler();
