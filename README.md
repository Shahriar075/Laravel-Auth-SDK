

# AuthSDKLibrary

## Project Title

AuthSDKLibrary: Streamlined OAuth and Custom Authentication for Laravel

## Project Description

AuthSDKLibrary is a PHP library designed to simplify the integration of OAuth-based authentication and custom authentication systems into Laravel applications. This library provides a set of easy-to-use methods for handling OAuth redirection, token management, user registration, login, and role assignment.

## Table of Contents

1.  [Project Overview](#project-overview)
2.  [Features](#features)
3.  [About AuthSDKLibrary](#about-authsdklibrary)
4.  [HLD AuthSDKLibrary](#hld-authsdklibrary)
5.  [Technologies Used](#technologies-used)
6.  [Requirements](#requirements)
7.  [Supporting API Setup](#supporting-api-setup)
8.  [Installation Instructions](#installation-instructions)
9.  [Configuration](#configuration)
10.  [Usage](#usage)
11.  [API Endpoints](#api-endpoints)
12.  [Testing the Library](#testing-the-library)
13.  [Conclusion](#conclusion)

## Project Overview

AuthSDKLibrary provides a robust solution for integrating both OAuth-based authentication and custom authentication systems into Laravel applications. It is designed to be flexible and easy to integrate, providing developers with essential tools for managing user authentication, tokens, and roles.

## Features

-   **OAuth Authentication**: Easily connect to OAuth providers like Google and GitHub.
-   **Custom Authentication**: Handle user registration, login, and role assignment.
-   **Token Management**: Support for refreshing OAuth tokens.
-   **Flexible Integration**: Designed to integrate seamlessly with Laravel projects.

## About AuthSDKLibrary

For more detailed information, visit the [AuthSDK Library Documentation](https://docs.google.com/document/d/1sl_t9UiL0WJkBNqS5e_YjNhBcrF3dnzAcfKK17414Hs/edit#heading=h.zrk7gwsd7ac).

## HLD AuthSDKLibrary

For a detailed overview of the system architecture and component interactions, refer to the [HLD of AuthSDK Library](https://drive.google.com/file/d/16EH5CHUkcExh-qzN9RNfP_EkbQ2G_KQE/view?usp=sharing)

## Technologies Used

-   **Backend**: PHP, Laravel
-   **Authentication**: OAuth 2.0, JWT

## Requirements

-   **PHP**: 7.4 or higher
-   **Laravel**: 8.x or higher
-   **Composer**: Dependency manager for PHP

## Supporting API Setup

### 1. Clone the Supporting API Repository

Begin by cloning the supporting API repository to set up the backend services required for the AuthSDKLibrary.

`git clone https://github.com/Shahriar-Amin-Joy/PHP-Authentication-SDK-Supporting-API-s.git`

Navigate to the cloned directory:

`cd PHP-Authentication-SDK-Supporting-API`

### 2. Set Up the Supporting API

After cloning the repository, follow these steps to configure and run the supporting API:

#### Install Dependencies

Use Composer to install the required PHP dependencies:

`composer install`

#### Configure Environment Variables

Create a new `.env` file from the example provided:

`cp .env.example .env`

Edit the `.env` file to include your specific configuration settings such as database credentials and API keys.

#### Generate Application Key

Generate a unique application key for your API to ensure security:

`php artisan key:generate`

#### Run Database Migrations

Set up the database by running the migrations:

`php artisan migrate`

#### Start the API Server

Launch the server to make the API operational:

`php artisan serve`

The API will be accessible at `http://localhost:8000` or your specified base URL.

## Installation Instructions

### 1. Clone or Download the Library

Clone or download the Authentication-SDK repository into your Laravel project. After cloning, you can make changes to any files or folders as per your requirements within the repository, except for the core `AuthSDKLibrary` folder, as it contains the essential files and structure for the library. Ensure that the `AuthSDKLibrary` folder remains intact to maintain the functionality of the authentication services.

`git clone https://github.com/Exabyting/Internal-reusable-SDKs.git`

### 2. Composer Autoload

Add the following to your `composer.json` file under the `autoload` section:

    "autoload": {
        "psr-4": {
            "AuthSDKLibrary\\": "AuthSDKLibrary/src"
        }
    }

Then, run the following command to install Composer’s autoload files:

`composer install`

### 3. Service Configuration

Add your OAuth providers' credentials in the `config/services.php` file of your Laravel project:

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_REDIRECT_URI'),
    ],
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

## Configuration

###  Environment Variables

Set the necessary environment variables in your `.env` file for each OAuth provider:


    GITHUB_CLIENT_ID=your-github-client-id
    GITHUB_CLIENT_SECRET=your-github-client-secret
    GITHUB_REDIRECT_URI=your-github-redirect-uri
    
    GOOGLE_CLIENT_ID=your-google-client-id
    GOOGLE_CLIENT_SECRET=your-google-client-secret
    GOOGLE_REDIRECT_URI=your-google-redirect-uri

### 2. Base URL

Configure the base URL for your API endpoints in the `config/services.php` file:

    'api' => [
        'base_url' => env('API_BASE_URL', 'your_local_host'),
    ],

## Usage

### 1. OAuthManager

#### Redirecting to Provider

    use AuthSDKLibrary\OAuthManager;
    
    $provider = 'github'; // Example: 'google' or 'github'
    $config = [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect_uri' => env('GITHUB_REDIRECT_URI'),
    ];
    
    $loginPageUrl = OAuthManager::redirectToProvider($provider, $config);
    
    return redirect($loginPageUrl);

#### Handling Provider Callback

    use AuthSDKLibrary\OAuthManager;
    
    $provider = 'github'; // Example: 'google' or 'github'
    $oauthManager = new OAuthManager($provider);
    $userData = $oauthManager->handleProviderCallback();
    
    return response()->json($userData);

#### Refreshing Tokens

    use AuthSDKLibrary\OAuthManager;
    
    $refreshToken = 'your_refresh_token';
    $response = OAuthManager::refreshToken($refreshToken);
    
    return response()->json($response);

### 2. AuthManager

#### Registering a User

    use AuthSDKLibrary\AuthManager;
    
    $data = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'role_id' => 2,
    ];
    
    $response = AuthManager::register($data);
    
    return response()->json($response);

#### Logging In a User

    use AuthSDKLibrary\AuthManager;
    
    $credentials = [
        'email' => 'john@example.com',
        'password' => 'password123',
    ];
    
    $response = AuthManager::login($credentials);
    
    return response()->json($response);

#### Assigning a Role to a User

    use AuthSDKLibrary\AuthManager;
    
    $data = [
        'name' => 'General',
    ];
    
    $response = AuthManager::addRoleForUser($data);
    
    return response()->json($response);

## API Endpoints

### OAuth Endpoints

-   **GET /auth/{provider}**
    -   Redirects to the OAuth provider's login page.
-   **GET /auth/{provider}/callback**
    -   Handles the OAuth provider's callback.

### Auth Endpoints

-   **POST /register**

    -   Registers a new user.
    -   **Request Body**: `name`, `email`, `password`, `role_id` (optional)
-   **POST /login**

    -   Logs in a user.
    -   **Request Body**: `email`, `password`
-   **POST /auth/refresh**

    -   Refreshes the user's access token.
    -   **Request Body**: `refresh_token`
-   **POST /add-user-role**

    -   Assigns a role to a user.
    -   **Request Body**: `role_name`

## Testing the Library

You can test the library using the provided routes in your `routes/api.php` file.

    use AuthSDKLibrary\AuthManager;
    use AuthSDKLibrary\OAuthManager;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    
    Route::post('/register', function(Request $request) {
        $data = AuthManager::register($request->all());
        return response()->json($data->getData());
    });
    
    Route::post('/login', function(Request $request) {
        $data = AuthManager::login($request->all());
        return response()->json($data->getData());
    });
    
    Route::post('auth/refresh', function(Request $request) {
        $refreshToken = $request->input('refresh_token');
        $data = AuthManager::refreshToken($refreshToken);
    
        return response()->json($data->getData());
    });
    
    Route::post('/add-user-role', function (Request $request){
        $data = AuthManager::addRoleForUser($request->all());
        return response()->json($data->getData());
    });
    
    Route::middleware('web')->group(function () {
        Route::get('auth/{provider}', function ($provider) {
            $config = [
                'client_id' => env('GITHUB_CLIENT_ID'),
                'client_secret' => env('GITHUB_CLIENT_SECRET'),
                'redirect_uri' => env('GITHUB_REDIRECT_URI'),
            ];
    
            $loginPageUrl = OAuthManager::redirectToProvider($provider, $config);
    
            return redirect($loginPageUrl);
        });
    
        Route::get('auth/{provider}/callback', function ($provider) {
            $oAuthManager = new OAuthManager($provider);
            return $oAuthManager->handleProviderCallback();
        });
    });



## Supporting API

The library is supported by an API implemented using Laravel's services, covering OAuth integration, custom authentication, and role management. Below are the key components and their functionalities:

-   **OAuthService**: Manages OAuth-related functionality, including redirecting to the provider, handling provider callbacks, and managing tokens.
-   **AuthProviderInterface**: Defines the contract for authentication providers, ensuring a consistent approach across different providers.
-   **ExceptionHandler**: Provides a unified way to handle exceptions and return error responses.
-   **User Registration**: Creates new user accounts with validation and exception handling for user details.
-   **User Login**: Authenticates users, generating access and refresh tokens while handling authentication errors.
-   **Token Refresh**: Refreshes access tokens using valid refresh tokens or provides a new token if necessary.
-   **Role Management**: Adds new roles to the system with validation and error handling.
-    **User**: Represents the user entity within the system, including attributes and relationships required for user management.
-   **UserToken**: Manages user tokens, including generation, storage, and refresh operations.


## Conclusion

AuthSDKLibrary is a powerful tool for Laravel developers looking to implement secure and flexible authentication systems. Whether you need OAuth integration or custom authentication, this library simplifies the process, enabling you to build robust applications quickly and efficiently.
