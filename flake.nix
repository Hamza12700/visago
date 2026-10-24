{
  description = "Laravel development environment";

  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixos-unstable";
  };

  outputs = { self, nixpkgs }:
    let
      systems = [
        "x86_64-linux"
        "aarch64-linux"
      ];

      forEachSystem = nixpkgs.lib.genAttrs systems;
    in {
      devShells = forEachSystem (system:
        let
          pkgs = import nixpkgs {
            inherit system;
          };

          php = pkgs.php84.withExtensions (
            { enabled, all }:
            enabled ++ (with all; [
              bcmath
              curl
              fileinfo
              intl
              mbstring
              pdo_sqlite
              tokenizer
              zip
            ])
          );
        in {
          default = pkgs.mkShell {
            packages = [
              php
              pkgs.php84Packages.composer
            ];

            shellHook = ''
              # Load project PHP overrides (raised upload limits for product images).
              export PHPRC="$PWD/.php"

              echo "PHP:      $(php --version | head -n 1)"
              echo "Composer: $(composer --version)"
              '';
          };
        });
    };
}
