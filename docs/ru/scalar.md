# Scalar

- [Описание](/ru/scalar#описание)
- [Стандартные](/ru/scalar#стандартные)
    - [Int](/ru/scalar#int)
    - [Float](/ru/scalar#float)
    - [String](/ru/scalar#string)
    - [Boolean](/ru/scalar#boolean)
    - [ID](/ru/scalar#id)
    - [Any](/ru/scalar#any)
- [Модификаторы](/ru/scalar#модификаторы)
    - [List](/ru/scalar#list)
    - [Non-Null](/ru/scalar#non-null)
- [Иерархия стандартных типов](/ru/scalar#иерархия-стандартных-типов)
- [Соответсвие скалярам PHP](/ru/scalar#соответсвие-скалярам-php)
- [Пользовательские типы](/ru/scalar#пользовательские-типы)
    - [Roadmap](/ru/scalar#roadmap)

## Описание

Скалярный тип представляет собой примивный специфицированный тип данных, который позволяет полностью 
описать одно целостное значение. Обычно скаляры описывают типы полей, которые содержат композитные 
(составные) типы, такие как [Объект](/ru/object), [Интерфейс](/ru/interface), 
[Директива](/ru/directive), [Инпут](/ru/input) или [Схема](/ru/schema).

## Стандартные

GraphQL предоставляет набор стандартных скалярных типов, встроенных в язык (спецификацию).

### Int

Положительное или отрицательное 32-битное целое число.

```graphql
type Example { 
    # Здесь и далее стоит обратить на указание типа поля.
    # В данном примере: "Int"
    fieldName: Int
}
```

#### Значение для типа Int

В качестве допустимого значения выступают числовой тип, который соответсвует `\-?\d+`.
Помимо этого значение должно находиться в пределах от -2147483648 до 2147483647, 
определённых 32х-битным целым.

```coercion
42         → Допустимое значение
-42        → Допустимое значение
0          → Допустимое значение
2147483648 → ERROR: Int cannot represent non 32-bit signed integer value
```

#### Приведение Int в ответах

Railgun принудительно приводит возвращаемый тип к Int, если это возможно. 
Если приведение невозможно - возникает соответсвующая ошибка. 
Правила приведения учитывают следующее:

- `Float` → `Int`
> В качестве результата используется только целочисленная часть числа с 
плавающей запятой, т.е. происходит отбрасывание дробной части (округление вниз).

- `String` → `Int`
> Допустимыми преобразованием считается то, где исходная строка содержит только 
символы от `0 до 9`, символ `-` (минус) и `.` (точка). Во всех остальных случаях будет выброшена ошибка приведения типа к `Int`.
 
- `Boolean` → `Int`
> Булев тип приводится к одному из значений: `false` → `0`, а `true` → `1`.

- `ID` → `Int`
> Полностью повторяет поведение преобразования из типа `String`.

- `Any` → `Int`
> Поддерживает PHP типы `float`, `int`, `string` и `bool`, и применяет соответсвующие преобразования. 

```coercion
"23"    → 23
"-23"   → -23
"-1.23" → -1
1.42    → 1
false   → 0
true    → 1
".23"   → ERROR: Int cannot represent non 32-bit signed integer value
"true"  → ERROR: Int cannot represent non 32-bit signed integer value
"test"  → ERROR: Int cannot represent non 32-bit signed integer value
```

#### Приведение Int в запросах

Все входящие значения для типа `Int` должны строго соответсвовать этому типу. 
В случае если клиент передаёт данные, отличные от `Int` (включая переполнение), то 
преобразования типов не учитываются и сервер возвращает ошибку запроса. 



### Float

Значение с плавающей запятой с двойной точностью.

```graphql
type Example {
    fieldName: Float 
}
```

#### Значение для типа Float

В качестве допустимого значения выступают числовой тип, который соответсвует `\-?0\.\d+`.
И определён в стандарте [IEEE 754](http://en.wikipedia.org/wiki/IEEE_floating_point) 
для чисел с двойной точностью дробной части. 

```coercion
0.42         → Допустимое значение
-0.42        → Допустимое значение
0.0          → Допустимое значение
42           → Допустимое значение (считается как "42.0")
```

#### Приведение Float в ответах

Railgun принудительно приводит возвращаемый тип к Float, если это возможно. 
Если приведение невозможно - возникает соответсвующая ошибка. 
Правила приведения учитывают следующее:

- `Int` → `Float`
> В качестве результата используется целое значение Int и добавляется дробная часть, равная 0. 

- `String` → `Float`
> Допустимыми преобразованием считается то, где исходная строка содержит только 
символы от `0 до 9`, символ `-` (минус) и `.` (точка). Во всех остальных случаях будет выброшена ошибка 
приведения типа к `Float`.
 
- `Boolean` → `Float`
> Булев тип приводится к одному из значений: `false` → `0.0`, а `true` → `1.0`.

- `ID` → `Float`
> Полностью повторяет поведение преобразования из типа `String`.

- `Any` → `Float`
> Поддерживает PHP типы `float`, `int`, `string` и `bool`, и применяет соответсвующие преобразования. 

```coercion
"23"    → 23.0
"-23"   → -23.0
"-1.23" → -1.0
42      → 42.0
false   → 0.0
true    → 1.0
"true"  → ERROR: Float cannot represent non numeric value
"test"  → ERROR: Float cannot represent non numeric value
```

#### Приведение Float в запросах

Все входящие значения для типа `Float` могут содержать как целую часть (определённую типом `Int`), 
так и дробные значение, специфицированные `Float`. Все целочисленные значения пробразуются во `Float`, 
путём добавления пустой, нулевой, дробной части, например: `1.0` для входящего значения `1`. 
Все остальные значения, включая строки, содержащие только числовые значения, вызывают ошибку запроса.

Если целочисленное входящее значение представляет собой число, превышающее допустимое стандартом
[IEEE 754](http://en.wikipedia.org/wiki/IEEE_floating_point), то сервер так же возвращает ошибку запроса.



### String

Скалярный тип String является текстовыми данными, представленными в виде последовательности символов UTF-8. 
Тип String чаще всего используется GraphQL для представления "человекочитаемого" текста.

```graphql
type Example {
    fieldName: String 
}
```

#### Значение для типа String

В качестве значения строки используется произвольная последовательность UTF-8 символов, 
обрамлённая двойными кавычками. Если в тексте содержатся символы кавычек, то они должны быть 
заэкранированы символом обратного слеша, например такая строка `"Это текст (блабла) и текст в кавчках (\"блабла\")"`
будет содержать `Это текст (блабла) и текст в кавчках ("блабла")`.

#### Приведение String в ответах

Каждый GraphQL скаляр может (и должен) приводиться к строке без ошибок.

- `Int` → `String`
> Свободно преобразуется в строковый формат "как есть".

- `Float` → `String`
> Свободно преобразуется в строковый формат "как есть".
 
- `Boolean` → `String`
> В отличие от PHP, где `true` преобразуется в `"1"`, а `false` преобразуется в `""` - булев тип в GraphQL 
преобразуется в соответсвующую буквенную последовательность: `"true"` для `true` и `"false"` в случае `false`.

- `ID` → `String`
> Свободно преобразуется в строковый формат "как есть".

- `Any` → `String`
> Свободно преобразуется в строковый формат "как есть".

#### Приведение String в запросах

Все входящие данные для типа `String` должны содержать только допустимые последовательности UTF-8. 
Все остальные входные значения приводят к ошибке запроса, указывающей на неправильный тип.

### Boolean

Булево значение: true или false.

```graphql
type Example {
    fieldName: Boolean 
}
```

#### Значение для типа Boolean

Булевский скалярный тип предоставляет значение `true` или `false`, 
соответсвующие положительному и отрицательному значению соответственно.

#### Приведение Boolean в ответах

Railgun приводит небулевые необработанные значения к `Boolean`.
Например, вполне допустимо вернуть `true` для любого ненулевого числа.
Если преобразование невозможно, то будет брошена соответсвующая ошибка.

- `Int` → `Boolean`
> Число, равное `0` (в т.ч.`-0`) будет преобразовано в `false`, 
в любом ином случае значение преобразуется в `true`. 

- `Float` → `Boolean`
> Число, строго равное `0.0` (в т.ч. `-0.0`) будет преобразовано в `false`, 
в любом ином случае значение преобразуется в `true`. 
 
- `String` → `Boolean`
> Строчка, строго соотвествующая `""` или `"0"` будет преобразована в `false`, 
в ином случае строка приведётся к значению `true`.

- `ID` → `Boolean`
> Приведение типа `ID` полностью соответствует типу `String`. 

- `Any` → `Boolean`
> Поведение зависит от внутреннего типа PHP.

#### Приведение Boolean в запросах

> TODO



### ID
 
Тип ID представляет собой уникальный идентификатор, часто используемый для восстановления объекта или в 
качестве ключа для кеша. Тип ID сериализуется так же, как String, однако определение его как 
идентификатора означает, что он **не** предназначен для чтения человеком.

```graphql
type Example {
    fieldName: ID 
}
```

#### Значение для типа ID

В качестве значения `ID` может выступать всё то, что допустимо 
в качестве значения для типа [`String`](/ru/scalar#string).

#### Приведение ID в ответах

Поведение типа `ID` во время сериализации полностью повторяет 
поведение типа [`String`](/ru/scalar#string).

#### Приведение ID в запросах

Поведение типа `ID` в запросе полностью повторяет 
поведение типа [`String`](/ru/scalar#string).



### Any

!> Этот тип не задекларирован в официальном стандарте, однако предполагаемый в одном из RFC.

В некоторых наших инструментах нужно иметь возможность передавать произвольное значение в 
качестве аргумента директивы, то есть значение, которое может быть любым и не может быть 
описано как один тип. Единственным обходным решением, которое можно придумать для выхода из 
ситуации - это определение скалярного JSON, который может принимать любое значение 
JSON, включая объекты и массивы.

[pull/325](https://github.com/facebook/graphql/pull/325) предполагает введение стандартного 
типа Any для реализации подобных задач. 

```graphql
type Example {
    fieldName: Any 
}
```

#### Значение для типа Any

Поведение типа `Any` полностью повторяет поведение ранее определённых 
типов в зависимости от значения.

#### Приведение Any в ответах

Поведение сериализации для типа `Any` полностью повторяет поведение ранее 
определённых типов в зависимости от декларации.

#### Приведение Any в запросах

Поведение типа `Any` полностью повторяет поведение ранее определённых 
типов (может содержать любой допустимый тип) в зависимости от декларации.



## Модификаторы

Помимо непосредственно самих скалярных типов GraphQL предоставляет два модификатора, 
которые описывают их характеристики.

### Non-Null

По-умолчанию все типы в GraphQL имеют значение `null`; 
Нулевое значение является допустимым ответом для всех вышеперечисленных типов. 
Чтобы объявить тип, который запрещает `null`, надо использовать модификатор Non-Null. 

Этот модификатор "обертывает" базовый тип, и действует идентично  
содержимому, за исключением того, что `null` не является допустимым. 
Заключительный восклицательный знак используется для обозначения поля, которое 
использует тип Non-Null, например: `name: ID!`.

### List

Список представляет собой специальный модификатор коллекции, который объявляет тип каждого 
элемента в списке (называемый типом элемента списка). 
Значения сериализуются как упорядоченные списки, где каждый элемент сериализуется 
в соответствии с типом этого элемента. Чтобы обозначить, что поле содержит список, 
тип элемента должен быть заключен в квадратные скобки следующим образом: `pets: [Pet]`.

#### Отличия между пустыми и нулевыми списками

Как можно заметить - синтаксис модификаторов Non-Null и List можно компановать. 
Ниже приведена таблица итога с обозначениями поведения типов в зависимости от 
их модификаторов, в качестве примера использован базовый скалярный тип `String`.

```coercion
String      → Строка, допускающая значение `null`.
String!     → Строка
[String]    → Значение `null`, либо список строк, допускающий значения `null`. 
[String!]   → Значение `null`, либо список строк
[String]!   → Cписок строк, допускающий значения `null`.
[String!]!  → Cписок строк
```

## Иерархия стандартных типов

Иерархия типов содержит структуру наследования GraphQL типов. Таким образом можно понять, 
что тип `Any` без потерь может содержать значение любого типа; `Float` может без потерь 
содержать значение типа `Int` (обратное преобразование может содержать потери точности) и т.д.  

```coercion
→ Any
    → Float
        → Int
    → Bool
    → String
        → ID
```

## Соответсвие скалярам PHP

Данная схема поможет понять каким образом соответсвуют стандартные скаляры PHP скалярам GraphQL.

```coercion
-------------------------------------------------------------------------
    GraphQL → PHP
-------------------------------------------------------------------------

    Any     → mixed     # http://php.net/manual/en/language.pseudo-types.php
    Float   → float     # http://php.net/manual/en/language.types.float.php
    Int     → integer   # http://php.net/manual/en/language.types.integer.php
    Bool    → boolean   # http://php.net/manual/en/language.types.boolean.php
    String  → string    # http://php.net/manual/en/language.types.string.php
    ID      → string    # http://php.net/manual/en/language.types.string.php

-------------------------------------------------------------------------
```

```coercion
-------------------------------------------------------------------------
    GraphQL → PHP
-------------------------------------------------------------------------

    boolean     → Boolean
    integer     → Int
    float       → Float
    string      → String
    array       → List | Object
    iterable    → List | Object
    object      → Object
    resource    → String
    null        → NULL
    callable    → Depends on the type-hint or the result of the closure call, if the type is not specified.

-------------------------------------------------------------------------
```


## Пользовательские типы

!> Далее описаны теоретические выкладки, которые никак не отображены на практике. 

Синтаксис GraphQL предполагает возможность создания своих скалярных типов, однако
Railgun на данный момент не предполагает их обработку. Ниже представлен валидный
синтаксис graphql, который будет правильно обработан компилятором, однако, на данный момент, это не 
возымеет никакого эффекта.

```graphql
scalar Example
```

### Roadmap

Ниже представлены возможные реализаци скаляров:

#### Вариант 1

Декларация скаляра подразумевает то, что такой тип будет обрабатываться _по-умолчанию_ как строковый:

_Декларация:_
```graphql
schema {
    query: Info
}

scalar DateTime

type Info {
    currentDateTime: DateTime!
}
```

_Запрос:_
```graphql
{
    currentDate
}
```

_Ответ:_
```json
{
    "currentDateTime": "21-08-2020 23:59:00"
}
```

Для предоставляения доступа к возможностям верификации и сериализации данных требуется указать 
ссылку на контекст исполнения этого скаляра. Для этого можно воспользоваться стандартным 
механизмом GraphQL - [директивами](/ru/directive). В этом случае стоит определить контекст
исполнения этого скаляра, например таким образом:

_Описание:_
```php
final class MyDateTimeType implements ScalarType
{
    /**
     * @param string|mixed $value
     * @return Carbon|\DateTimeInterface
     */
    public function parse($value): \DateTimeInterface
    {
        return Carbon::parse($value);    
    }
    
    /**
     * @param Carbon|\DateTimeInterface|mixed $value
     * @return string
     * @throws InvalidArgumentException
     */
    public function serialize($value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return Carbon::create($value)->format(Carbon::RFC3339);
        }
        
        try {
            return (string)$value;
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException('Type value must be compatible with datetime');
        }
    }
}
```

_Декларация:_

```graphql
scalar DateTime @use(class: "MyDateTimeType")
```