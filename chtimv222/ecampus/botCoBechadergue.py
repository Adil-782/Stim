from selenium import webdriver # type: ignore
from webdriver_manager.firefox import GeckoDriverManager# type: ignore
from selenium.webdriver.firefox.service import Service# type: ignore
from selenium.webdriver.firefox.options import Options# type: ignore
from selenium.webdriver.common.by import By# type: ignore
import time

# URL du site web avec le formulaire de connexion
url = "http://localhost/ecampus/page_connexion.php"

# Configurer les options pour Firefox
firefox_options = Options()
# firefox_options.headless = True  # Décommente pour exécuter Firefox sans fenêtre graphique (mode sans tête)

# Configure le service pour geckodriver
service = Service(GeckoDriverManager().install())

# Initialiser le WebDriver avec les options et le service
driver = webdriver.Firefox(service=service, options=firefox_options)

# Ouvrir la page web
driver.get(url)

time.sleep(5)

# Trouver les éléments du formulaire
email_field = driver.find_element("name", "email")  # Remplace par l'attribut correct
password_field = driver.find_element("name", "password")  # Remplace par l'attribut correct

# Rentrer les identifiants
email_field.send_keys("bastien.bechadergue@uvsq.fr")
password_field.send_keys("bastien123")

# Attendre quelques secondes avant de soumettre le formulaire
time.sleep(3)

# Soumettre le formulaire
login_button = driver.find_element("name", "submit")  # Remplace par l'attribut correct
login_button.click()

# Attendre quelques secondes pour que la redirection ou la connexion soit terminée
time.sleep(5)

# Ouvrir un nouvel onglet et naviguer vers Gmail
driver.execute_script("window.open('https://mail.google.com', '_blank');")

# Attendre que le nouvel onglet se charge
time.sleep(3)

# Passer au nouvel onglet
driver.switch_to.window(driver.window_handles[1])


# Trouver le champ d'adresse email sur Gmail et y entrer l'adresse de Bastien
email_input = driver.find_element(By.NAME, "identifier")
email_input.send_keys("bastienbechadegue@gmail.com")

# Cliquer sur le bouton "Suivant"
next_button = driver.find_element(By.ID, "identifierNext")
next_button.click()

# Attendre le chargement de la page du mot de passe
time.sleep(8)  # Ajuster le d  lai si n  cessaire

# Entrer le mot de passe en utilisant By.CSS_SELECTOR
password_input = driver.find_element(By.CSS_SELECTOR, "input[type='password']")
password_input.send_keys("bastienbechadegue123")

# Cliquer sur le bouton "Suivant" pour se connecter
next_button = driver.find_element(By.ID, "passwordNext")
next_button.click()

# Attendre quelques secondes pour voir le r  sultat
time.sleep(15)


#Ouvrir le lien du site voyage japon
driver.execute_script("window.open('http://localhost/Japon/index.html', '_blank');")
driver.switch_to.window(driver.window_handles[2])

time.sleep(3)

button = driver.find_element("name", "btn-attaque")  # Remplace par l'attribut correct
button.click()

time.sleep(3)

# Fermer le navigateur
driver.quit()
