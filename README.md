# Generate Image from URL

### Requirements

- WKHTMLTOIMAGE

### Usage

```php
use Metawesome\ImgFromUrl\Img;

$img = new Img();
$img->fromUrl('https://www.google.com');
$img->saveAs(storage_path('google.jpg'));
```