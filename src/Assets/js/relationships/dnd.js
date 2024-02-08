
function allowDrop(event) {
    event.preventDefault();
}

function drag(event, modelName) {
    event.dataTransfer.setData("origin", modelName);
}

function getMethodNameInput(relationship, originModel) {
    var name = getMethodName(relationship, originModel);
    return `<input type="text" class="focused px-2 text-sm text-white rounded bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer w-40 " placeholder="functionName" value="${name}"/> `
}

function getMethodName(relationship, originModel) {
    return "test"
}

function getMethodBlock(relationship, relationshipMethod, originModel) {
    const tab = '&nbsp;&nbsp;&nbsp;&nbsp;';

    return `<div>
    <div class="flex flex-row flex-start items-center">
      <p class="text-sm font-bold mr-2">public function</p>
        ${getMethodNameInput(relationship, originModel)}   
      <p class="text-sm font-bold">( ) {</p> 
   </div>

   <div class="flex flex-row flex-start items-center">
    ${tab}
    <p class="text-sm font-bold">return $this->${relationshipMethod}(${originModel}::class, </p>
      ${getMethodRelationshipInputs()}
    <p>);</p>
    </div>
    <p>}</p>    
</div>
<div class="delete-btn">${deleteComponent}</div>`;
}

function getMethodRelationshipInputs() {
    return `<input type="text" class="focused block mx-1 px-2 w-full text-sm text-white rounded bg-gray-700  border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer w-40" placeholder="foreign_key"/> 
    <input type="text" class="focused block px-2 w-full mx-1 text-sm text-white rounded bg-gray-700  border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer w-40"placeholder="local_key"/>`
}

function drop(event, relationship, model, relationshipMethod) {
    event.preventDefault();

    var origin = event.dataTransfer.getData("origin");
    var element = document.querySelector(`.js-${model}-${relationship}`);

    const newClass = document.createElement('div');
    
    newClass.innerHTML = getMethodBlock(relationship, relationshipMethod, model);
    newClass.className = 'bg-white p-2 rounded-md mb-2 flex flex-row justify-between items-center cursor-pointer';
    newClass.setAttribute('data-origin', origin);

    newClass.querySelector('.delete-btn').addEventListener('click', function () {
        this.parentNode.remove();
    });

    element.appendChild(newClass);
}