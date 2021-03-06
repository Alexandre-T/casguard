Configuration file
==================

You can download the [default and complete config file](./configuration_sample.yaml).

Here is [another sample](./configuration_complex.yaml) with two allowed clients for logout. 

Default and complete configuration:
--- 
```yaml
#/config/packages/cas_guard.yaml
cas_guard:

    # Enter the certificate to identify the CAS server. Set to false if you do not use it. In production, you must use one.
    certificate:          false # Example: certificate path

    # Enter a filename to trace log or leave empty to use the default filename. Set to false to disable debug function.
    debug:                '' # Example: phpcas-trace.log

    # Enter the hostname of the CAS server.
    hostname:             ~ # Required, Example: example.org

    # Enter the language for phpcas error and trace. Possible value could be read here: https://github.com/apereo/phpCAS/blob/master/source/CAS.php#L215 .
    language:             CAS_Languages_English # One of "CAS_Languages_English"; "CAS_Languages_French"; "CAS_Languages_Greek"; "CAS_Languages_German"; "CAS_Languages_Japanese"; "CAS_Languages_Spanish"; "CAS_Languages_Catalan"; "CAS_Languages_ChineseSimplified", Example: CAS_Languages_French

    # Server cas port
    port:                 443 # Example: 443

    # REQUEST_PATH of the CAS server.
    url:                  cas/login # Example: cas/login

    # Route node.
    route:

        # Name of your home route.
        homepage:             homepage # Example: home

        # Name of your login route.
        login:                security_login # Example: my_login_route

        # Name of the route where user is redirected after successful logout.
        logout:               home # Example: home

    # If true phpcas trace will be more explicit.
    verbose:              false # Example: true

    # Version of the CAS Server.
    version:              '3.0' # One of "3.0"; "2.0"; "1.0", Example: 3.0

    # Logout node.
    logout:

        # Are the CAS server and your application supporting single sign out signal?
        supported:            true # Example: false

        # Are your application handling single sign out signal?
        handled:              true # Example: false

        # List of host names allowed to send logout requests.
        allowed_clients:      [] # Example: ["server1.example.org", "server2.example.org"]

        # true if you want to provide the url to the user to go back to your application after logout.
        redirect_url:         false # Example: true
        
```
