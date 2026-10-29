import { defineRailway, github, preserve, project, service } from "railway/iac";

export const partial = "mcp-myarchivist-ai";

export default defineRailway(() => {
  const mcp = service("mcp.myarchivist.ai", {
    source: github("Astrotomic/mcp.myarchivist.ai", { branch: "main", checkSuites: false }),
    build: { buildEnvironment: "V3", builder: "DOCKERFILE", dockerfilePath: "/Dockerfile" },
    healthcheck: "/up",
    healthcheckTimeout: 30,
    replicas: { "us-west2": 1 },
    domains: ["mcp.myarchivist.ai"],
    deploy: { restartPolicyType: "ALWAYS" },
    networking: { privateNetworkEndpoint: "mcpmyarchivistai" },
    env: {
      APP_DEBUG: preserve(), APP_ENV: preserve(), APP_FAKER_LOCALE: preserve(),
      APP_FALLBACK_LOCALE: preserve(), APP_KEY: preserve(), APP_LOCALE: preserve(),
      APP_MAINTENANCE_DRIVER: preserve(), APP_NAME: preserve(), APP_URL: preserve(),
      ARCHIVIST_APP_URL: preserve(), ARCHIVIST_BASE_URL: preserve(), BCRYPT_ROUNDS: preserve(),
      BROADCAST_CONNECTION: preserve(), CACHE_STORE: preserve(), DB_CONNECTION: preserve(),
      FILESYSTEM_DISK: preserve(), LOG_CHANNEL: preserve(), LOG_DEPRECATIONS_CHANNEL: preserve(),
      LOG_LEVEL: preserve(), LOG_STACK: preserve(), MAIL_MAILER: preserve(),
      MEMCACHED_HOST: preserve(), OPENAI_APPS_CHALLENGE_TOKEN: preserve(), PORT: preserve(),
      QUEUE_CONNECTION: preserve(), REDIS_CLIENT: preserve(), REDIS_HOST: preserve(),
      REDIS_PASSWORD: preserve(), REDIS_PORT: preserve(), SENTRY_LARAVEL_DSN: preserve(),
      SESSION_DOMAIN: preserve(), SESSION_DRIVER: preserve(), SESSION_ENCRYPT: preserve(),
      SESSION_LIFETIME: preserve(), SESSION_PATH: preserve(),
    },
  });

  return project("archivist-api", { resources: [mcp] });
});
