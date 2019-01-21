FROM jimschubert/8-jdk-alpine-mvn:1.0 as builder

WORKDIR /build
COPY pom.xml /build
RUN mvn verify clean --fail-never

COPY . /build/
RUN mvn package


FROM openapitools/openapi-generator-cli:v3.3.4 as runner

ENV OPENAPI_GEN_ROOT_DIR /opt/openapi-generator
ENV OPENAPI_GEN_JARS_DIR ${OPENAPI_GEN_ROOT_DIR}/jars
ENV OPENAPI_GEN_NAME     swiftype-php-openapi-generator

RUN mkdir -p ${OPENAPI_GEN_JARS_DIR} && cp ${OPENAPI_GEN_ROOT_DIR}/modules/openapi-generator-cli/target/*.jar ${OPENAPI_GEN_JARS_DIR}

COPY --from=builder /build/target/${OPENAPI_GEN_NAME}-1.0.0.jar ${OPENAPI_GEN_JARS_DIR}/${OPENAPI_GEN_NAME}.jar
COPY ./docker-entrypoint.sh /usr/local/bin