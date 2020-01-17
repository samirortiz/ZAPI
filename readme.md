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

- [Lista de imóveis elegíveis ZAP filtrados por bairro](http://localhost:8000/portal/zap/neighborhood/socorro)
- [Lista de imóveis elegíveis VivaReal filtrados por bairro](http://localhost:8000/portal/vivareal/neighborhood/socorro)

## Observações
- Utilizar o .env commitado com valores de ambiente para check de Bounding Box e urls de fonte de dados
- A aplicação tem 60 segundos de cache para o request na fonte de dados
- Quando utilizado o filtro por bairro, serão mostrados os primeiros 100 registros

## Testes
 - vendor/bin/phpunit
 
