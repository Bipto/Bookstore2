FROM httpd:2.4

RUN sed -i \
    -e 's/^#LoadModule proxy_module/LoadModule proxy_module/' \
    -e 's/^#LoadModule proxy_fcgi_module/LoadModule proxy_fcgi_module/' \
    -e 's/^#LoadModule vhost_alias_module/LoadModule vhost_alias_module/' \
    -e 's/^#LoadModule rewrite_module/LoadModule rewrite_module/' \
    /usr/local/apache2/conf/httpd.conf

COPY vhost.conf /usr/local/apache2/conf/extra/vhost.conf

RUN echo "Include conf/extra/vhost.conf" >> /usr/local/apache2/conf/httpd.conf