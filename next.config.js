/** @type {import('next').NextConfig} */
const isProd = process.env.NODE_ENV === "production";

const nextConfig = {
  experimental: {
    appDir: true,
    distDir: "build",
    assetPrefix: isProd ? "http://localhost:3000" : undefined,
  },
};

module.exports = nextConfig;
