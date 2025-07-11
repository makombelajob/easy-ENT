function themeDark() {
	let currentTheme = localStorage.theme ?? "light";

	const themeSwitch = document.querySelector("#theme-mode");

	applyTheme();

	themeSwitch?.addEventListener("click", function () {
		currentTheme = (currentTheme === "light") ? "dark" : "light";
		localStorage.theme = currentTheme;
		applyTheme();
	});

	function applyTheme() {
		const iconTheme = `images/sprites.svg#${currentTheme}`;
		const useElement = themeSwitch?.querySelector("use");

		if (useElement) {
			useElement.setAttribute("href", iconTheme);
		}

		const linkStyle = document.querySelector("#style");
		
		if (linkStyle) {
			const CSSFile = (currentTheme === "light")
			? linkStyle.dataset.styleLight
			: linkStyle.dataset.styleDark;


			linkStyle.href = CSSFile;
		}

		//document.documentElement.setAttribute("data-theme", currentTheme); // facultatif mais utile
	}
}

