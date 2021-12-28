clean:
	rm -rf build

build:
	mkdir build
	ppm --no-intro --compile="src" --directory="build"

update:
	ppm --generate-package="src"

install:
	rm frontend/index.php; cp make_assets/build.php frontend/index.php;
	ppm --no-intro --no-prompt --fix-conflict --install="build/net.intellivoid.coffeehouse_web.ppm"
	rm frontend/index.php; cp make_assets/live.php frontend/index.php;

install_fast:
	rm frontend/index.php; cp make_assets/build.php frontend/index.php;
	ppm --no-intro --no-prompt --fix-conflict --skip-dependencies --install="build/net.intellivoid.coffeehouse_web.ppm"
	rm frontend/index.php; cp make_assets/live.php frontend/index.php;

live:
	while inotifywait -qqre modify "src"; do make clean update build install_fast; done