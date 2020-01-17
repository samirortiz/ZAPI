# API Grupo ZAP

## Instalação
- git clone https://samirortiz@bitbucket.org/samirortiz/zapi.git
- composer install

## Subir o serviço
- php -S localhost:8000 -t public

## Utilização
- [Lista de imóveis elegíveis ZAP](http://localhost:8000/portal/zap)
- [Lista de imóveis elegíveis VivaReal](http://localhost:8000/portal/vivareal)

- [Lista de imóveis elegíveis ZAP por página](http://localhost:8000/portal/zap/page/2)
- [Lista de imóveis elegíveis VivaReal por página](http://localhost:8000/portal/vivareal/page/2)

## Observações
- Utilizar o .env commitado com valores de ambiente para check de Bounding Box

## Testes
 - vendor/bin/phpunit
 
