openssl ca -revoke ./demoCA/server.crt -passin pass:0000000
openssl ca -gencrl -out ./demoCA/crl/pem.crl -passin pass:0000000
