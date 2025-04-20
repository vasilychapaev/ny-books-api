# Pangolin Assessment: NYT Best Sellers List & Filter

Please create a Laravel JSON API acting as a wrapper/middleman around the New York Times Best Sellers History API.

● All code should be well tested.
● Tests should pass without valid NYT API credentials or an active internet connection.
● Tests should consider edge and failure cases.
● Laravel's Form Requests, HTTP Tests, and HTTP Client should be used. Leveraging additional features is encouraged.

We care deeply about API integrations, both internal and external. Respecting the brief time constraints, consider API versioning, breaking change strategies, caching, code reusability, cross-environment testing, and more.



# 1. Create New York Times API Credentials

Create your own API credentials to access the NYT API:

1. Create a New York Times developer account: https://developer.nytimes.com/accounts/create

2. Go to create a New App: https://developer.nytimes.com/my-apps/new-app

3. Enable the Books API.

4. Create your app.

5. Copy your API key locally.



# 2. Create the Laravel JSON API

Create an endpoint around the NYT Best Sellers endpoint. The endpoint should support the following subset of the NYT API's Query Parameters:

● author : string
● isbn[] : string
● title : string
● offset : integer



# 3. Delivery

Please publish your code to a Git service and send us a link.


