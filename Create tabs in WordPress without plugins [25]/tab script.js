<style>
:root {
  --tab-main: #bd06f3;
  --tab-gray: #ccc;
}

.red-tab {
    overflow: hidden;
    border-bottom: 1px solid var(--tab-gray);
    background-color: white;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-start!important;
    align-content: space-between;
    flex-direction: row;
    margin-bottom: 1px!important;
    gap: 0px!important;
}
.red-tab div{
  float: left;
  border-bottom: 3px solid transparent;
  cursor: pointer;
  transition: 0.3s;
  font-size: 17px;
}
.red-tab a.wp-block-button__link {
    color: black!important;
    background-color: transparent!important;
}
.red-tab div a {
    padding: 20px 40px!important;
}
.red-tab div:hover a, .red-tab div.active a {
    color: var(--tab-main)!important;
}
.red-tab div.active, .red-tab div:hover {
    color: var(--tab-main)!important;
    border-bottom: 3px solid var(--tab-main);
    z-index: 3;
}
.tab-content {
    display: none;
    padding: 35px 15px;
    border-top: none;
    box-shadow: 0 12px 25px -10px #00000040;
	background-color: white;
}
.active {
    display: block;
}
</style>



<script>
document.addEventListener("click", e => {  if (e.target.parentElement.classList.contains("tab-title")  ) { redTab(e) }  });

[...document.querySelectorAll("div.red-tab div")].forEach((tabTile, index) => {
    tabTile.classList.toggle('active', index == 0)
  });
[...document.querySelectorAll(".tab-content")].forEach((tabcontent, index) => {
  tabcontent.classList.toggle('active', index == 0)
  });
function redTab(e) {	
  let tabTiles = [...document.querySelectorAll("div.red-tab div")];
  let tabcontents = [...document.querySelectorAll(".tab-content")];
  let activeTabIndex = tabTiles.findIndex(tab => { return tab == e.target.parentElement })
  tabTiles.forEach((tabTile, index) => {
    tabTile.classList.toggle('active', index === activeTabIndex)
  })
  tabcontents.forEach((tabcontent, index) => {
  tabcontent.classList.toggle('active', index === activeTabIndex)
  })
}
</script>
